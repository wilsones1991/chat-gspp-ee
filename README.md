# ChatGSPP EE

ChatGSPP EE is an ExpressionEngine add-on that inserts a chatbox interface for ChatGSPP. ChatGSPP is the Goldman School of Public Policy's implementation of [PrivateGPT](https://github.com/zylon-ai/private-gpt), a "production-ready AI project that allows you to ask questions about your documents using the power of Large Language Models (LLMs)."

ChatGSPP EE is built on top of the [PrivateGPT SDK Demo App](https://github.com/frgarciames/privategpt-react), which utilizes the PrivateGPT Web SDK.

## Installation

1. Add `system/user/chat-gspp` to your add-on folder.
2. Add `themes/user/chat-gspp` to your themes folder.

## Development

1. Navigate to resources.

   ```
   cd resources
   ```
   
3. Install dependencies.

   ```
   pnpm install
   ```
4. Run dev server.

   ```
   pnpm dev
   ```

5. Add development assets to html head.

   ```
      <script type="module">
       import RefreshRuntime from 'http://localhost:5173/@react-refresh'
       RefreshRuntime.injectIntoGlobalHook(window)
       window.$RefreshReg$ = () => {}
       window.$RefreshSig$ = () => (type) => type
       window.__vite_plugin_react_preamble_installed__ = true
     </script>
     <script type="module" src="http://localhost:5173/@vite/client"></script>
     <script type="module" src="http://localhost:5173/src/main.tsx"></script> 
     <link rel="stylesheet" href="/assets/css/base-accessibility-updates.css?v={gv_cachebreaker}12">
   ```

   
## TODO

- [ ] Add settings
   - [ ] PrivateGPT API Endpoint
   - [ ] Styling
