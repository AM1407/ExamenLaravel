# 📋 TaskFlow — Team Task Manager

> A lightweight, no-nonsense task management app built with Laravel 13. Designed for small teams who need clarity on who's doing what, how long it takes, and when it's done.

---

## 🧠 Concept

### What problem are you solving?

Small teams waste enormous amounts of time on unclear task ownership. Who is working on what? When did they start? Is it done yet? Most teams fall back on WhatsApp messages, sticky notes, or bloated tools like Jira that take longer to configure than the task itself takes to complete.

**TaskFlow** cuts through that noise. It gives a team a single place to:
- Create and assign tasks
- Track how long tasks actually take
- Get notified the moment something is completed
- Keep a conversation thread alive on every task

### Why does this matter in Belgium?

Belgium has one of the **highest densities of SMEs (small and medium enterprises) in Europe**. According to Statbel, over 99% of Belgian businesses are SMEs — the vast majority of which have fewer than 10 employees. These teams cannot afford enterprise tooling like Jira or Monday.com, and they don't have a dedicated project manager to maintain them.

Belgian work culture also values **transparency and accountability** in team settings — a culture of showing your work, not just your results. A tool that shows who started what, when, and how long it took fits naturally into that dynamic.

Whether it's a digital agency in Ghent, a logistics startup in Antwerp, or a consultancy in Brussels — TaskFlow solves a real, daily problem for Belgian teams.

### Who are the users?

| Role | Description |
|---|---|
| **Scrum Master / Team Lead** | Creates tasks, assigns them to team members, monitors progress and time |
| **Team Member** | Picks up tasks, updates status, leaves comments with updates |
| **Stakeholder** | Views task overview to understand what the team is working on |

---

## ⚙️ Installation

### Requirements

- PHP 8.2+
- Composer
- MySQL 8.0+
- Laravel 13

### Step 1: Clone the repository

```bash
git clone https://github.com/your-username/taskflow.git
cd taskflow
```

### Step 2: Install dependencies

```bash
composer install
```

### Step 3: Set up your environment file

Request the `.env` file from the project owner and place it in the root of the project. It will not be included in the repository for security reasons.

If you want to configure it manually, copy the example file:

```bash
cp .env.example .env
```

Then open `.env` and fill in the following:

```env
APP_NAME=TaskFlow
APP_KEY=                        # Generated in Step 4
APP_DEBUG=true
APP_URL=http://localhost

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=taskflow
DB_USERNAME=root
DB_PASSWORD=your_password

SLACK_WEBHOOK_URL=              # Your Slack incoming webhook URL
```

> ⚠️ Never commit your `.env` file to GitHub. It contains sensitive credentials. The `.env.example` file is the safe, public version with placeholder values.

### Step 4: Generate application key

```bash
php artisan key:generate
```

### Step 5: Create the database

In MySQL, create a new database:

```sql
CREATE DATABASE taskflow;
```

### Step 6: Run migrations

```bash
php artisan migrate
```

This will create the following tables:
- `users` — team members
- `tasks` — tasks with status and time tracking
- `comments` — discussion threads on tasks

### Step 7: Seed the database

```bash
php artisan db:seed
```

This will populate the database with:
- 5 realistic team members
- 25 real-world tasks across bugs, features, DevOps and testing
- Comments on active and completed tasks
- Realistic time tracking data (started/completed timestamps)

### Step 8: Start the development server

```bash
php artisan serve
```

Visit `http://localhost:8000` in your browser.

---

## 🧪 Usage

### Navigating the app

| Page | URL | Description |
|---|---|---|
| Task Overview | `/tasks` | Full list of all tasks with status and assignee |
| Create Task | `/tasks/create` | Form to create and assign a new task |
| Edit Task | `/tasks/{id}/edit` | Edit task details, update status, view time tracking, add comments |

### Example scenarios

#### Scenario 1: Starting a new sprint

1. Go to **Create Task**
2. Enter the task title, description, and assign it to a team member
3. Click **Create Task** — it appears in the list with status `Pending`

#### Scenario 2: A developer picks up a task

1. Open the task from the overview
2. Change the status to **In Progress**
3. TaskFlow automatically records the `started_at` timestamp
4. The time tracking card now shows how long the task has been running

#### Scenario 3: Task is completed

1. Open the task
2. Change status to **Completed**
3. TaskFlow records the `completed_at` timestamp and calculates total time spent
4. A **Slack notification** is automatically sent to the team channel:
   > ✅ *"Fix login button not responding on mobile"* — completed by Alice Johnson

#### Scenario 4: Leaving a team update

1. Open any task in progress
2. Scroll to the **Comments** section
3. Type an update and click **Post Comment**
4. All comments are visible to the full team with timestamps

#### Scenario 5: Spotting overdue work

1. Open the task overview
2. Tasks that have been **In Progress for over 24 hours** show an overdue warning
3. The team lead can reassign or follow up

---

## 🔥 Momentum Factor

### What makes this app powerful?

**1. Time tracking is automatic.**
There's no manual clock-in/clock-out. The moment a task moves to `In Progress`, Carbon records the timestamp. The moment it's marked `Completed`, the clock stops. You get accurate data without any extra effort from the team.

**2. Slack integration closes the feedback loop.**
The team doesn't need to check the app to know when something is done. The Slack webhook fires automatically on completion, keeping everyone in the loop without adding to anyone's workload.

**3. It's built on Laravel 13 best practices.**
- Eloquent relationships (hasMany, belongsTo)
- RESTful resource controllers
- Carbon for all date logic
- Proper migrations and seeders
- Environment-based configuration

**4. The data tells a story.**
After a few sprints, you have real data: how long tasks actually take, who completes work fastest, which categories of tasks (bugs vs features) consume the most time. That's the foundation of continuous improvement.

### How could this scale into a real product?

| Feature | Description |
|---|---|
| **User authentication** | Each team member logs in to their own account with Laravel Breeze or Sanctum |
| **Multiple teams / workspaces** | Companies manage multiple projects under one account |
| **Role-based access** | Scrum Masters can create/delete, team members can only update their own tasks |
| **Priority levels** | Critical, High, Medium, Low — with visual indicators |
| **Deadline tracking** | Due dates with Carbon-powered overdue alerts sent to Slack |
| **Reporting dashboard** | Charts showing velocity, average task duration, team workload |
| **Mobile app** | API-first structure makes a React Native or Flutter app straightforward |
| **GitHub integration** | Link tasks to pull requests and auto-close them on merge |
| **Export to CSV** | Management gets weekly reports without touching the app |

At its core, TaskFlow is a focused, functional product. It solves one problem well. That's exactly the kind of foundation a real SaaS product is built on.

---

## 🛠️ Tech Stack

| Layer | Technology |
|---|---|
| Framework | Laravel 13 |
| Language | PHP 8.2 |
| Database | MySQL 8 |
| Frontend | Blade Templates |
| Date handling | Carbon |
| Notifications | Slack Incoming Webhooks |
| Pagination | Laravel Paginator (Bootstrap 5) |

---

## 👥 Authors

- Alessio 

---

*Built for the Laravel exam project.
