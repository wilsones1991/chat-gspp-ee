import { FormEvent, useEffect, useRef, useState } from 'react';
import { useChat, useFiles } from 'privategpt-sdk-web/react';

import { Badge } from '@/components/ui/badge';
import { Label } from '@/components/ui/label';
import { PrivategptApi } from 'privategpt-sdk-web';
import { PrivategptClient } from '@/lib/pgpt';
import { Textarea } from '@/components/ui/textarea';
import { cn, transformToUrl } from '@/lib/utils';
import { marked } from 'marked';
import { useLocalStorage } from 'usehooks-ts';
import { Button } from './ui/button';
import { ChatButton } from './ui/chatButton';
import { StopCircle } from 'lucide-react';

const MODES = [
  {
    value: 'query',
    title: 'Query docs',
    description:
      'Uses the context from the ingested documents to answer the questions',
  },
  {
    value: 'search',
    title: 'Search files',
    description: 'Fast search that returns the 4 most related text chunks',
  },
  {
    value: 'chat',
    title: 'LLM Chat',
    description: 'No context from files',
  },
] as const;

export function Chat() {
  const scrollRef = useRef<HTMLDivElement>(null);

  const scrollToBottom = () => {
    if (scrollRef.current) {
      scrollRef.current.scrollTop = scrollRef.current.scrollHeight;
    }
  };

  const [isVisible, setIsVisible] = useState(false);

  const toggleVisibility = () => {
    setIsVisible((prev) => !prev);
  };

  const messageRef = useRef<HTMLTextAreaElement>(null);
  const [mode] = useLocalStorage<(typeof MODES)[number]['value']>(
    'pgpt-chat-mode',
    'query',
  );

  const [environment] = useLocalStorage('pgpt-url', '');
  const [input, setInput] = useLocalStorage('input', '');
  const [systemPrompt] = useLocalStorage<string>(
    'system-prompt',
    ``,
  );

  const [messages, setMessages, clearChat] = useLocalStorage<
    Array<
      PrivategptApi.OpenAiMessage & {
        sources?: PrivategptApi.Chunk[];
      }
    >
  >('messages', []);
  const [selectedFiles] = useLocalStorage<string[]>(
    'selected-files',
    [],
  );
  
  const { files } =
    useFiles({
      client: PrivategptClient.getInstance(environment),
      fetchFiles: true,
    });

  const { completion, isLoading } = useChat({
    client: PrivategptClient.getInstance(environment),
    messages: messages.map(({ sources: _, ...rest }) => rest),
    onFinish: ({ completion: c, sources: s }) => {
      addMessage({ role: 'assistant', content: c, sources: s });
      setTimeout(() => {
        messageRef.current?.focus();
      }, 100);
    },
    useContext: mode === 'query',
    enabled: ['query', 'chat'].includes(mode),
    includeSources: mode === 'query',
    systemPrompt,
    contextFilter: {
      docsIds: ['query', 'search'].includes(mode)
        ? selectedFiles.reduce((acc, fileName) => {
            const groupedDocs = files?.filter((f) => f.fileName === fileName);
            if (!groupedDocs) return acc;
            const docIds = [] as string[];
            groupedDocs.forEach((d) => {
              docIds.push(...d.docs.map((d) => d.docId));
            });
            acc.push(...docIds);
            return acc;
          }, [] as string[])
        : [],
    },
  });

  const handleSubmit = (event: FormEvent<HTMLFormElement>) => {
    event.preventDefault();
    if (!input) return;
    const content = input.trim();
    addMessage({ role: 'user', content });
    if (mode === 'search') {
      searchDocs(content);
    }
  };

  const addMessage = (
    message: PrivategptApi.OpenAiMessage & {
      sources?: PrivategptApi.Chunk[];
    },
  ) => {
    setMessages((prev) => [...prev, message]);
    setInput('');
  };

  const searchDocs = async (input: string) => {
    const chunks = await PrivategptClient.getInstance(
      environment,
    ).contextChunks.chunksRetrieval({ text: input });
    const content = chunks.data.reduce((acc, chunk, index) => {
      return `${acc}**${index + 1}.${chunk.document.docMetadata?.file_name}${
        chunk.document.docMetadata?.page_label
          ? ` (page ${chunk.document.docMetadata?.page_label})** `
          : '**'
      }\n\n ${chunk.document.docMetadata?.original_text} \n\n  `;
    }, '');
    addMessage({ role: 'assistant', content });
  };

  useEffect(() => {
    scrollToBottom();
  }, [completion, messages]);

  useEffect(() => {
    // Add message with role of assistant to start the conversation
      if (messages.length === 0) {
        addMessage({
          role: 'assistant',
          content: `Hello! My name is ChatGSPP, and I'm here to help you with your questions. 
Feel free to ask me anything. I can assist you with a variety of topics, 
including admissions, courses, faculty, and more.

**Please note that I am an experimental language model AI and may not get everything right.**
Always verify the information I provide with official sources.

Please type your question below and I'll do my best to help you.`,
        })
      }

  }, []);

  return (
        <div className="twp">
          <div className="fixed bottom-20 right-4 w-full max-w-sm max-h-[500px] mx-auto overflow-y-auto bg-white rounded-xl shadow-gspp z-[1000]" ref={scrollRef}>
            {isVisible && (
            <div className="relative flex-col flex h-full space-y-4 rounded-xl bg-muted/50 p-4 lg:col-span-2">
              <div className="flex-1">
                <div className="flex flex-col space-y-4">
                  {messages.map((message, index) => (
                    <div
                      key={index}
                      className={cn(
                        'h-fit p-3 grid gap-2 shadow-lg rounded-xl w-fit',
                        {
                          'self-start': message.role === 'user',
                          'self-end bg-berkeleyBlue w-full':
                            message.role === 'assistant',
                        },
                      )}
                    >
                      <Badge variant="outline" className="w-fit bg-muted/100">
                        {message.role === 'user' ? 'Guest' : 'ChatGSPP'}
                      </Badge>
                      <div
                        className="prose text-black marker:text-black"
                        dangerouslySetInnerHTML={{
                          __html: marked.parse(message.content || ''),
                        }}
                      />
                      {message.sources && message.sources?.length > 0 && (
                        <div>
                          <p className="font-bold">Sources:</p>
                          <ul>
                            {message.sources.map((source) => (
                              <li key={source.document.docId} className="list-outside list-disc ml-[1em] text-[1rem]">
                                <a className="no-twp page-content link link--text" href={`https://${transformToUrl(source.document.docMetadata?.file_name as string)}`}>
                                  {transformToUrl(source.document.docMetadata?.file_name as string)}
                                </a>
                              </li>
                            ))}
                          </ul>
                        </div>
                      )}
                    </div>
                  ))}
                  {completion && (
                    <div className="h-fit p-3 grid gap-2 shadow-lg rounded-xl w-full self-end bg-berkeleyBlue">
                      <Badge variant="outline" className="w-fit bg-muted/100">
                        assistant
                      </Badge>
                      <div
                        className="prose marker:text-black"
                        dangerouslySetInnerHTML={{
                          __html: marked.parse(completion),
                        }}
                      />
                    </div>
                  )}
                </div>
              </div>
              <form
                className="relative rounded-lg"
                x-chunk="dashboard-03-chunk-1"
                onSubmit={handleSubmit}
              >
                <Label htmlFor="message" className="sr-only">
                  Message
                </Label>
                <Textarea
                  ref={messageRef}
                  disabled={isLoading}
                  id="message"
                  placeholder="Type your message here..."
                  className="min-h-12 resize-none border bg-background focus-within:ring-1 focus-within:ring-ring p-3 shadow-none focus-visible:ring-0"
                  value={input}
                  name="content"
                  onKeyDown={(event) => {
                    if (event.key === 'Enter' && !event.shiftKey) {
                      event.preventDefault();
                      event.currentTarget.form?.dispatchEvent(
                        new Event('submit', { bubbles: true }),
                      );
                    }
                  }}
                  autoFocus
                  onChange={(event) => setInput(event.target.value)}
                />
                <div className="flex flex-wrap items-center pt-3">
                  {isLoading ? (
                    <Button
                      type="button"
                      onClick={stop}
                      size="sm"
                      className="ml-auto gap-1.5 mt-4 text-white hover:text-black hover:bg-berkeleyBlue hover:border hover:border-black"
                    >
                      Stop
                      <StopCircle className="size-3.5" />
                    </Button>
                  ) : (
                    <Button type="submit" className="w-full text-white hover:text-black hover:bg-berkeleyBlue hover:border hover:border-black" disabled={!input.trim()}>
                      Send Message
                    </Button>
                  )}
                  <Button className="w-full mt-4 bg-white text-black border border-black hover:bg-berkeleyBlue" onClick={clearChat}>
                    Clear
                  </Button>
                </div>
              </form>
            </div>
            )}
            
          </div>
          <div className="fixed z-[1000] bottom-0 right-0 m-4 w-[48px] h-[48px]">
              <ChatButton onClick={toggleVisibility} />
          </div>
      </div>

  );
}
