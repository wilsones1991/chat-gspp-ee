// src/types.d.ts
interface ReadableStream<T = any> {
    [Symbol.asyncIterator](): AsyncIterableIterator<T>;
}