import { Chat } from '@/components/chat';
import { checkIsPgptHealthy } from '@/lib/pgpt';
import { useEffect } from 'react';
import { useSessionStorage } from 'usehooks-ts';

export const RootPage = () => {
  const [environment, setEnvironment, deleteEnvironment] = useSessionStorage<
    string | undefined
  >('pgpt-url', undefined);

  const checkPrivateGptHealth = async (env: string) => {
    try {
      const isHealthy = await checkIsPgptHealthy(env);
      if (!isHealthy) {
        console.log('The Private GPT instance is not healthy');
        deleteEnvironment();
        return;
      }
      return <Chat />;
    } catch {
      deleteEnvironment();
      console.log('The Private GPT instance is not healthy');
      return;
    }
  };

  useEffect(() => {
    if (!environment) {
        const url = 'https://gspp-ai.gspp.berkeley.edu';
        setEnvironment(url);
        checkPrivateGptHealth(url);
    } else {
      checkPrivateGptHealth(environment);
    }
  }, []);

  if (environment) return <Chat />;
  return null;
};
