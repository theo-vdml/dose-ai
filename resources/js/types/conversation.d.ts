type MessageRole = 'user' | 'assistant' | 'system';

type Message = {
    id: string;
    created_at: string;
    updated_at: string;
    conversation_id?: Conversation['id'];
    parent_message_id: Message['id'] | null;
    role: MessageRole;
    content: string;
    reasoning: string | null;
    error?: string | null;
};

type Conversation = {
    id: string;
    created_at: string;
    updated_at: string;
    user_id: number;
    title: string | null;
    model_id: string;
    last_message_at: string | null;
    messages: Message[];
    current_message_id: string | null;
};

type Model = {
    id: string;
    name: string;
    description: string;
};
