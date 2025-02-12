# Polyfills & Browser Compatibility

This project includes polyfills to ensure compatibility with browsers that do not fully support modern JavaScript features.

## Asynchronous Iteration (`for await...of`)

### Issue
Some browsers, notably Safari, do not fully support asynchronous iteration (`for await...of`) in JavaScript. This causes issues in features that rely on iterating over async iterables, such as fetching paginated API responses.

### Solution
A polyfill for asynchronous iteration is included in `polyfills.js` to ensure compatibility.

### Implementation
The polyfill leverages `Symbol.asyncIterator` to enable async iteration:

```js
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
