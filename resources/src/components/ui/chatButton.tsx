import chatGsppIcon from '../../assets/chatgspp-icon.png';

function ChatButton({...props}) {
  return (
    <button className="chat-button" {...props}>
        <img className="transition filter hover:brightness-75" src={chatGsppIcon} alt="Chat with GSPP" width="48" height="48" />
    </button>
  );
}

export { ChatButton };