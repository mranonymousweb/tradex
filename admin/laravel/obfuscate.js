// obfuscate.js
import fs from 'fs';
import path from 'path';
import { fileURLToPath } from 'url';
import JavaScriptObfuscator from 'javascript-obfuscator';

const __filename = fileURLToPath(import.meta.url);
const __dirname = path.dirname(__filename);

const inputDir = path.join(__dirname, 'nodeServices');
const outputDir = path.join(__dirname, 'dist');

// حذف و ساخت مجدد مسیر خروجی
if (fs.existsSync(outputDir)) {
    fs.rmSync(outputDir, { recursive: true });
}
fs.mkdirSync(outputDir, { recursive: true });

// توابع
function obfuscateFile(filePath, relativePath) {
    const content = fs.readFileSync(filePath, 'utf-8');
    const obfuscated = JavaScriptObfuscator.obfuscate(content, {
        compact: true,
        controlFlowFlattening: true
    });

    const outputPath = path.join(outputDir, relativePath);
    const outputDirPath = path.dirname(outputPath);

    fs.mkdirSync(outputDirPath, { recursive: true });
    fs.writeFileSync(outputPath, obfuscated.getObfuscatedCode());
}

function walk(dir, relativeRoot = '') {
    const files = fs.readdirSync(dir);
    for (const file of files) {
        const fullPath = path.join(dir, file);
        const relativePath = path.join(relativeRoot, file);
        if (fs.statSync(fullPath).isDirectory()) {
            walk(fullPath, relativePath);
        } else if (file.endsWith('.js')) {
            obfuscateFile(fullPath, relativePath);
        }
    }
}

// شروع کار
walk(inputDir);
console.log('✅ Obfuscation complete.');
