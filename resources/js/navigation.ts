import * as lucid from 'lucide-vue-next';
import { dashboard } from './routes';
import conversations from './routes/conversations';
import { NavItem } from './types';

export default [
    {
        title: 'New Chat',
        href: conversations.new(),
        icon: lucid.MessageCirclePlus,
    },
] as NavItem[];
