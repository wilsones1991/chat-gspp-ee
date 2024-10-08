import 'vite/modulepreload-polyfill';
import './index.css';

import ReactDOM from 'react-dom/client';
import { RootPage } from './pages/root.tsx';

ReactDOM.createRoot(document.getElementById('chat-gspp')!).render(<RootPage />);
