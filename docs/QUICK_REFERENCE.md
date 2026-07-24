# SHARK GPT - Quick Reference & API Guide

##  Key Routes (All Protected by Auth)

### Chat Management
```
GET    /dashboard                  → DashboardComponent (Dashboard)
GET    /chats/create              → ChatCreateComponent (New Chat)
GET    /chats/{uuid}              → ChatShowComponent (Chat View)  MAIN
POST   /chats/{uuid}/messages     → MessageController@store
DELETE /chats/{uuid}              → ChatController@destroy
POST   /chats/{uuid}/pin          → ChatController@pin
POST   /chats/{uuid}/archive      → ChatController@archive
```

### Projects
```
GET    /projects                  → ProjectIndexComponent
GET    /projects/create           → ProjectCreateComponent
GET    /projects/{project}        → ProjectShowComponent
POST   /projects                  → ProjectController@store
```

### Settings
```
GET    /settings/profile          → ProfileComponent (Livewire)
GET    /settings/password         → password.blade.php (Volt)
GET    /settings/appearance       → appearance.blade.php (Volt)
GET    /settings/language         → language.blade.php (Volt)
GET    /settings/two-factor       → two-factor.blade.php (Volt)
```

### Subscription
```
GET    /subscription/pricing      → SubscriptionController@pricing
GET    /subscription/checkout/{plan} → CheckoutComponent
POST   /subscription/process-payment → processPayment()
```

---

##  AI Models Available

Via **OpenRouter SDK:**

| Model | ID | Speed | Cost | Best For |
|-------|----|----|------|----------|
| GPT-4o | `gpt-4o` | Slow | High | Complex reasoning |
| GPT-4 Turbo | `gpt-4-turbo` | Medium | Medium | Balanced |
| GPT-3.5 Turbo | `gpt-3.5-turbo` | Fast | Low | Quick responses |
| Claude 3 Opus | `claude-3-opus` | Slow | High | Long-form content |
| Claude 3 Sonnet | `claude-3-sonnet` | Medium | Medium | Balanced |
| Llama 3.1 70B | `llama-3.1-70b` | Fast | Low | Open source |
| Mistral 7B | `mistral-7b` | Fast | Low | Lightweight |

---

##  Database Schema Quick Reference

### Users Table
```sql
id, name, email, password, theme, avatar, language, google_avatar, 
last_seen_at, email_verified_at, two_factor_secret, remember_token, created_at, updated_at
```

### Chats Table
```sql
id, uuid (route key), user_id (FK), title, project_id (FK), 
last_message_at, last_read_at, created_at, updated_at
```

### Messages Table ✨ (Updated)
```sql
id, chat_id (FK), content, role (user/assistant), 
status (sent/delivered/read) [NEW],
delivered_at (timestamp) [NEW], read_at (timestamp) [NEW],
created_at, updated_at
```

### Projects Table
```sql
id, user_id (FK), title, description, created_at, updated_at
```

### Orders Table
```sql
id, user_id (FK), customer, customer_avatar, date, status, 
status_color, amount, created_at, updated_at
```

### Subscriptions Table
```sql
id, user_id (FK), plan, stripe_subscription_id, status, 
current_period_start, current_period_end, created_at, updated_at
```

---

##  Key Livewire Components

### ChatShowComponent
**Location:** `app/Livewire/ChatShowComponent.php`

**Properties:**
```php
public Chat $chat;
public $messages = [];
public $messageText = '';
public $selectedModel = 'gpt-4o';
public $isLoading = false;
public $showTypingIndicator = false;
```

**Public Methods:**
```php
public function sendMessage()           // Send user message → get AI response
public function generateAIResponse()    // Call AI service
public function loadMessages()          // Fetch all chat messages
public function markMessagesAsDelivered() // Update status
public function markMessagesAsRead()    // Set as read
public function deleteMessage($id)      // Delete message
public function setSelectedModel($model) // Change AI model
public function refreshMessages()       // Manual refresh (polled every 500ms)
```

**Events (Dispatched):**
```php
$this->dispatch('message-sent')->broadcast();
$this->dispatch('user-typing')->broadcast();
$this->dispatch('user-stopped-typing')->broadcast();
$this->dispatch('refresh-messages');
```

### DashboardComponent
**Location:** `app/Livewire/DashboardComponent.php`

**Shows:**
- Recent chats
- Quick stats
- Recent orders
- Projects overview

---

##  Message Status Lifecycle

```
┌─────────────────────────────────────────────────────┐
│                 Message Creation                     │
│  Message::create([status => 'sent'])                │
└────────────────┬────────────────────────────────────┘
                 │
                 ▼
        ┌────────────────┐
        │ SENT ✓         │ (Created, gray tick)
        └────────┬───────┘
                 │
                 ▼ (Message loads in another user's chat)
        ┌────────────────┐
        │ DELIVERED ✓✓   │ (Gray double tick)
        │ delivered_at set
        └────────┬───────┘
                 │
                 ▼ (User reads message)
        ┌────────────────┐
        │ READ ✓✓        │ (Blue double tick)
        │ read_at set
        └────────────────┘
```

**Mark as Read in PHP:**
```php
$message->markAsRead();        // Sets status='read', read_at=now()
$message->markAsDelivered();   // Sets status='delivered', delivered_at=now()

// Or bulk update
Message::where('chat_id', $chatId)
    ->where('status', '!=', 'read')
    ->update(['status' => 'read', 'read_at' => now()]);
```

---

##  UI Components Location

### Views
```
resources/views/
├── livewire/
│   ├── chats/show.blade.php        [MAIN] Chat interface
│   ├── settings/*.blade.php         Settings pages
│   └── auth/*.blade.php             Fortify auth forms
├── components/
│   ├── layouts/app.blade.php       Main layout
│   └── layouts/guest.blade.php     Auth layout
└── subscription/
    ├── pricing.blade.php
    ├── checkout.blade.php
    └── success.blade.php
```

### Key CSS Classes
```blade
<!-- Dark Mode Toggle -->
x-data="{ darkMode: true }"
:class="darkMode ? 'bg-[#171717] text-white' : 'bg-gray-50'"

<!-- Message Bubble -->
{{ $message['role'] === 'user' ? 'justify-end' : 'justify-start' }}

<!-- Blue Tick -->
@if($message['status'] === 'read')
    <span class="text-blue-600">✓✓</span>
@elseif($message['status'] === 'delivered')
    <span class="text-gray-500">✓✓</span>
@endif

<!-- Typing Animation -->
<div class="animate-bounce" style="animation-delay: 0ms;"></div>
```

---

##  Testing Commands

```bash
# Run all tests
php artisan test

# Run specific test file
php artisan test tests/Feature/ChatTest.php

# Run with coverage
php artisan test --coverage

# Run specific test method
php artisan test tests/Feature/ChatTest.php --filter test_user_can_send_message

# Generate test file
php artisan make:test ChatTest --feature
```

---

##  Debugging Tips

### Check Message Status
```bash
php artisan tinker

# Get chat and its messages
$chat = Chat::first();
$chat->messages()->get(['id', 'content', 'status', 'read_at']);

# Mark all as read
$chat->markAllAsRead();

# Check unread count
$chat->unreadCount();
```

### Clear Caches
```bash
# Livewire cache
php artisan livewire:cache-component-syntax

# View cache
php artisan view:cache
php artisan view:clear

# Config cache
php artisan config:cache
php artisan config:clear
```

### Monitor Real-time
```bash
# Watch logs
tail -f storage/logs/laravel.log | grep "ChatShowComponent"

# WebSocket connections
php artisan websockets:serve

# Queue jobs
php artisan queue:work
```

---

##  Performance Optimization Checklist

- [ ] Added database indexes on `messages.chat_id` and `chats.user_id`
- [ ] Implemented message pagination (50 per page)
- [ ] Enabled query caching for recent messages
- [ ] Optimized N+1 queries with eager loading
- [ ] Compressed images before upload
- [ ] Minified frontend assets (npm run build)
- [ ] Enabled gzip compression on server
- [ ] Set up CDN for static assets
- [ ] Configured lazy loading for message content
- [ ] Reduced polling interval to 500ms (balance vs server load)

---

##  Deployment Environment Variables

```env
# AI Service
OPENROUTER_API_KEY=sk-xxxxxxxx
GROQ_API_KEY=xxx...

# Broadcasting (for real-time)
BROADCAST_DRIVER=pusher
PUSHER_APP_ID=xxxxx
PUSHER_APP_KEY=xxxxx
PUSHER_APP_SECRET=xxxxx
PUSHER_HOST=api-xxxxxxx.pusher.com
PUSHER_PORT=443
PUSHER_SCHEME=https

# Payment (Stripe)
STRIPE_PUBLIC_KEY=pk_live_xxxxx
STRIPE_SECRET_KEY=sk_live_xxxxx

# Email
MAIL_DRIVER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=465
MAIL_USERNAME=xxxxx
MAIL_PASSWORD=xxxxx
MAIL_ENCRYPTION=ssl
MAIL_FROM_ADDRESS=noreply@shark.app

# Database (Production)
DB_HOST=db.example.com
DB_PORT=3306
DB_DATABASE=shark_prod
DB_USERNAME=shark_user
DB_PASSWORD=strong_password

# App
APP_ENV=production
APP_DEBUG=false
APP_URL=https://shark.app

# Session
SESSION_DRIVER=database
CACHE_DRIVER=redis
QUEUE_CONNECTION=redis
```

---

##  Support & Resources

### Docs
- [Laravel Docs](https://laravel.com/docs)
- [Livewire Docs](https://livewire.laravel.com)
- [OpenRouter API](https://openrouter.ai/docs)

### Community
- Discord: [Laravel Community](https://discord.gg/laravel)
- GitHub Issues: Report bugs with reproduction steps
- Stack Overflow: Tag with `laravel`, `livewire`

### Monitoring
- **Sentry:** Error tracking `sentry:laravel`
- **Bugsnag:** Real-time error monitoring
- **New Relic:** Application performance monitoring

---

**Last Updated:** April 21, 2026
**Version:** 1.0 | Production Ready ✓
