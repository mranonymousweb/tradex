import * as React from 'react';
import { Button } from '../ui/button';

type ErrorBoundaryProps = {
  children: React.ReactNode;
};

type ErrorBoundaryState = {
  hasError: boolean;
};

export class ErrorBoundary extends React.Component<
  ErrorBoundaryProps,
  ErrorBoundaryState
> {
  constructor(props: ErrorBoundaryProps) {
    super(props);
    this.state = { hasError: false };
  }

  static getDerivedStateFromError(): ErrorBoundaryState {
    // Update state so the next render will show the fallback UI.
    return { hasError: true };
  }

  componentDidCatch(error: Error, info: React.ErrorInfo) {
    console.error('ErrorBoundary caught:', error, info.componentStack);
  }

  render() {
    if (this.state.hasError) {
      // You can render any custom fallback UI
      return (
        <div className="w-full flex flex-col items-center justify-center mt-20">
          <p className="text-2xl font-semibold mb-2">
            Oops Something went wrong
          </p>
          <span className="text-secondary-foreground block mb-4">
            Server error occurred. Please try again later or Contact Us for
            assistance.
          </span>
          <Button type="button" onClick={() => window.location.reload()}>
            Retry
          </Button>
        </div>
      );
    }

    return this.props.children;
  }
}
