type Message = {
    id: number;
    created_at: string;
    updated_at: string;
    conversation_id: number;
    parent_message_id?: number;
    role: 'user' | 'assistant' | 'system';
    content: string;
};

type Conversation = {
    id: number;
    created_at: string;
    updated_at: string;
    user_id: number;
    title?: string;
    model_id: string;
    forked_from_conversation_id?: number;
    last_message_at?: string;
    messages: Message[];
};
