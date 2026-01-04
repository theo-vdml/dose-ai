import * as lucid from 'lucide-vue-next';
import { dashboard } from './routes';
import conversations from './routes/conversations';
import { NavItem } from './types';

export default [
    {
        title: 'Dashboard',
        href: dashboard(),
        icon: lucid.LayoutGrid,
    },
    {
        title: 'Conversations',
        href: conversations.index(),
        icon: lucid.MessageCircle,
    },
    {
        title: 'New Conversation',
        href: conversations.new(),
        icon: lucid.PlusCircle,
    },
] as NavItem[];
