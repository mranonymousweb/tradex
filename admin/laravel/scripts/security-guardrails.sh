#!/usr/bin/env bash
# ---------------------------------------------------------------------------
# Security guardrails — Arya Exchange
#
# Each check below corresponds to a real vulnerability found in the 2026 audit.
# The point is regression prevention: these bugs were shipped once, so the
# pipeline should refuse to ship them again.
#
# Exit 1 = blocking. Run locally with:  bash scripts/security-guardrails.sh
# ---------------------------------------------------------------------------
set -uo pipefail

FAIL=0
red()  { printf '\033[31m%s\033[0m\n' "$1"; }
grn()  { printf '\033[32m%s\033[0m\n' "$1"; }
ylw()  { printf '\033[33m%s\033[0m\n' "$1"; }

# Strip commented-out PHP lines so dead code doesn't produce noise.
# (grep output is "path:line:content" — the content starts after the 2nd colon.)
live() { grep -vE '^[^:]+:[0-9]+:\s*(//|#|\*|/\*)' || true; }

# report <id> <description> <matches>
report() {
  local id="$1" desc="$2" matches="$3"
  if [ -n "$matches" ]; then
    red "✗ $id — $desc"
    echo "$matches" | sed 's/^/    /'
    echo
    FAIL=1
  else
    grn "✓ $id — $desc"
  fi
}

echo "=== Security guardrails ==="
echo

# --- V-19 / V-23: admin actions must target the session, never a URL param ---
M=$(grep -rnE 'public function (updatePassword|updateProfile|toggleGoogle2fa|toggleOtp|verify2FA)\(Admin \$admin' app/Http/Controllers/Admin/ 2>/dev/null || true)
report "V-19/V-23" "admin self-service actions must not bind Admin from the route" "$M"

# --- V-01 / V-07: resources must be scoped to the authenticated user ---
M=$(grep -rnE '(Market|FeatureOrder|UserCard|UserTransaction|Swap)::find\(\$request->' app/Http/Controllers/User/ 2>/dev/null | live)
report "V-01/V-07" "user-facing lookups must be scoped by user_id (no bare ::find(\$request->…))" "$M"

# --- V-03 / V-12 / V-31: money amounts must reject zero and negatives ---
M=$(grep -rnE "'amount'\s*=>\s*\[[^]]*'required'[^]]*\]" app/Http/Requests/ 2>/dev/null \
    | grep -vE "gt:0|min:0\.0|decimal|'numeric',\s*'gt" || true)
report "V-03/V-12/V-31" "every 'amount' rule needs numeric + gt:0" "$M"

# --- V-04: assignment used where comparison was meant, inside a condition ---
M=$(grep -rnE 'if\s*\(\s*\$[a-zA-Z_>-]+->status\s*=\s*[A-Za-z]' app/ 2>/dev/null | live)
report "V-04" "no assignment (=) inside an if() status check" "$M"

# --- V-05: sensitive endpoints must be throttled ---
for r in 'verify' 'sign-in' 'forget-password' 'send-otp'; do
  if ! grep -qE "'$r'.*throttle|throttle.*'$r'" routes/api/v1/auth.php 2>/dev/null; then
    red "✗ V-05 — route '$r' has no throttle middleware"
    FAIL=1
  else
    grn "✓ V-05 — route '$r' is throttled"
  fi
done

# --- V-05: OTP codes must use a CSPRNG ---
M=$(grep -rn 'rand(100000' app/ 2>/dev/null | live)
report "V-05" "OTP generation must use random_int(), not rand()" "$M"

# --- V-30: no hardcoded OTP outside a local-only guard ---
M=$(grep -rnE '=\s*123456' app/Services/ 2>/dev/null | live | grep -v "environment('local')" || true)
report "V-30" "no hardcoded OTP value" "$M"

# --- V-29: no real APP_KEY in the example env ---
M=$(grep -nE '^APP_KEY=base64:.+' .env.example 2>/dev/null || true)
report "V-29" ".env.example must ship an empty APP_KEY" "$M"

# --- V-30: example env must not default to demo/debug ---
M=$( { grep -nE '^APP_ENV=(demo|local)' .env.example 2>/dev/null || true; \
       grep -nE '^APP_DEBUG=true' .env.example 2>/dev/null || true; } )
report "V-30/V-11" ".env.example must default to production + debug off" "$M"

# --- V-20: proxies must not be trusted wholesale ---
M=$(tr -d '
' < bootstrap/app.php 2>/dev/null | grep -oE "trustProxies\([^)]*at:\s*'\*'" || true)
report "V-20" "trustProxies must list real proxy CIDRs, not '*'" "$M"

# --- V-28: SVG uploads are executable markup ---
M=$(grep -rn 'mimes:.*svg' app/Http/Requests/ 2>/dev/null | live)
report "V-28" "SVG must not be an accepted upload type" "$M"

# --- V-39: dead security middleware (unconditional early return) ---
M=$(for f in app/Http/Middleware/*.php; do
      awk '/public function handle/{h=1; next}
           h && /^[[:space:]]*(\{|\/\/|\/\*|\*|$)/ {next}
           h {if ($0 ~ /^[[:space:]]*return \$next\(\$request\);/) print FILENAME": "FNR": unconditional early return"; h=0}' "$f"
    done 2>/dev/null || true)
report "V-39" "no middleware short-circuits before its checks" "$M"

# --- V-38: every declared middleware must actually be registered ---
M=$(for f in app/Http/Middleware/*.php; do
      c=$(basename "$f" .php)
      case "$c" in AppMiddleware) continue;; esac
      grep -q "$c" app/Http/Middleware/AppMiddleware.php || echo "$c is never registered in AppMiddleware"
    done 2>/dev/null || true)
report "V-38" "no orphaned middleware (declared but never wired up)" "$M"

# --- V-02 / V-34: unauthenticated API routes need an explicit allowlist ---
ALLOWED='kyc-webhook|amlbot/monitoring|/user'
M=$(grep -nE "^Route::(post|get|match|any)\(" routes/api.php 2>/dev/null \
    | grep -vE "middleware" | grep -vE "$ALLOWED" || true)
report "V-02/V-34" "public API routes must be reviewed (add to allowlist deliberately)" "$M"

# --- V-21: password reset must invalidate existing sessions/tokens ---
BLOCK=$(awk '/function forgetPassword/{f=1} f{print} f&&/^    }/{exit}'         app/Http/Controllers/AuthController.php 2>/dev/null || true)
if echo "$BLOCK" | grep -q 'Hash::make'; then
  if ! echo "$BLOCK" | grep -q 'tokens()->delete()'; then
    red "✗ V-21 — password reset does not revoke existing tokens"
    FAIL=1
  else
    grn "✓ V-21 — password reset revokes tokens"
  fi
fi

echo
if [ "$FAIL" -eq 1 ]; then
  red "=== FAILED — security guardrails tripped. Fix the findings above. ==="
  ylw "Context for each ID is in docs/security-report.html"
  exit 1
fi
grn "=== All guardrails passed ==="
