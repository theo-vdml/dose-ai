import * as lucid from 'lucide-vue-next';
import { dashboard } from './routes';
import conversations from './routes/conversations';
import quickPrompt from './routes/quick-prompt';
import { NavItem } from './types';

export const mainNavigationItems: NavItem[] = [
    {
        title: 'Dashboard',
        href: dashboard(),
        icon: lucid.LayoutGrid,
    },
    {
        title: 'Quick Prompt',
        href: quickPrompt.index(),
        icon: lucid.Rabbit,
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
];

export const footerNavigationItems: NavItem[] = [
    {
        title: 'Github Repo',
        href: 'https://github.com/laravel/vue-starter-kit',
        icon: lucid.Folder,
    },
    {
        title: 'Documentation',
        href: 'https://laravel.com/docs/starter-kits#vue',
        icon: lucid.BookOpen,
    },
];
