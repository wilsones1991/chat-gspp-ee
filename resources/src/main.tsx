
// Asynchronous iteration is not yet implemented by all browsers, notably Safari.
// This polyfill adds support for async iteration on ReadableStream.
// https://developer.mozilla.org/en-US/docs/Web/API/ReadableStream#browser_compatibility

if (!ReadableStream.prototype[Symbol.asyncIterator]) {
    ReadableStream.prototype[Symbol.asyncIterator] = async function* () {
        const reader = this.getReader();
        try {
            while (true) {
                const { value, done } = await reader.read();
                if (done) {
                    return;
                }
                yield value;
            }
        } finally {
            reader.releaseLock();
        }
    };
}

import 'vite/modulepreload-polyfill';
import './index.css';

import ReactDOM from 'react-dom/client';
import { RootPage } from './pages/root.tsx';

ReactDOM.createRoot(document.getElementById('chat-gspp')!).render(<RootPage />);
