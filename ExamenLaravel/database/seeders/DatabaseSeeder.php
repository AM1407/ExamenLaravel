<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Task;
use App\Models\Comment;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{

    public function run(): void
    {

        $users = User::factory()->createMany([
            [
                'name' => 'Alice Johnson',
                'email' => 'alice@company.com',
            ],
            [
                'name' => 'Bob Smith',
                'email' => 'bob@company.com',
            ],
            [
                'name' => 'Carol Williams',
                'email' => 'carol@company.com',
            ],
            [
                'name' => 'David Brown',
                'email' => 'david@company.com',
            ],
            [
                'name' => 'Emma Davis',
                'email' => 'emma@company.com',
            ],
        ]);


        $taskTitles = [

            'Fix login button not responding on mobile',
            'Database connection timeout on prod',
            'User avatar images not loading',
            'API rate limiting not working correctly',
            'Memory leak in background jobs',


            'Add two-factor authentication',
            'Implement dark mode toggle',
            'Create user dashboard analytics',
            'Add email notification preferences',
            'Build CSV export functionality',


            'Optimize database queries for reports',
            'Refactor authentication middleware',
            'Update dependencies to latest versions',
            'Improve error messages in forms',
            'Add comprehensive API documentation',


            'Set up automated backups',
            'Configure CloudFlare CDN',
            'Create staging environment',
            'Set up monitoring and alerts',
            'Implement database replication',


            'Write unit tests for payment module',
            'Add integration tests for API',
            'Create E2E tests for checkout flow',
            'Test mobile responsiveness',
            'Load testing on production server',
        ];

        $descriptions = [
            'Users on iOS are experiencing issues with the login button. Investigation shows it\'s a touch event handler problem.',
            'Database is timing out during peak hours. Need to investigate connection pool settings and query optimization.',
            'Static assets are loading but images are returning 404. Check CDN configuration and asset pipeline.',
            'Rate limiting headers are not being sent correctly. Review the middleware implementation.',
            'Memory usage grows over time in worker processes. Investigate for unreleased resources.',
            'Critical security feature. Research best practices and implement TOTP/SMS options.',
            'Users have requested a dark mode. Need to implement theme switcher with localStorage persistence.',
            'Create a comprehensive dashboard showing key metrics and user activity trends.',
            'Let users control which notifications they receive and via which channels.',
            'Allow users to export their data and reports in CSV format for offline analysis.',
            'Some reports are running slowly. Index optimization and query rewriting needed.',
            'Current auth middleware is getting complex. Time for a refactor using middleware classes.',
            'Several packages are outdated. Update and test thoroughly for compatibility.',
            'Generic error messages are confusing users. Add specific, helpful guidance.',
            'API documentation is scattered. Create comprehensive OpenAPI/Swagger docs.',
            'Implement automated daily backups to S3 with retention policy.',
            'Move static assets to CDN for faster delivery and reduced server load.',
            'Environment for testing before production deployment. Replica of prod setup.',
            'Set up Datadog/New Relic alerts for critical metrics and error rates.',
            'Set up master-slave replication for high availability and failover.',
            'Ensure authentication module is thoroughly tested at unit level.',
            'Test all API endpoints for correct responses and error handling.',
            'Verify checkout flow works end-to-end across browsers.',
            'Test responsive design on various screen sizes and orientations.',
            'Run load tests to ensure server can handle peak traffic.',
        ];

        $statuses = ['pending', 'in-progress', 'done'];

        foreach ($taskTitles as $index => $title) {
            $task = Task::create([
                'title' => $title,
                'description' => $descriptions[$index] ?? 'No description provided',
                'status' => $this->getRealisticStatus($index),
                'user_id' => $users->random()->id,
            ]);

            if ($task->status !== 'pending' && rand(0, 1)) {
                $this->addRealisticComments($task, $users);
            }
        }
    }

    private function getRealisticStatus(int $index): string
    {
        // 60% pending, 30% in_progress, 10% completed
        if ($index % 10 === 0) {
            return 'completed';
        } elseif ($index % 3 === 0) {
            return 'in_progress';
        }
        return 'pending';
    }

    private function addRealisticComments(Task $task, $users): void
    {
        $commentTexts = [
            'Started working on this. Will need to review the codebase first.',
            'Found the issue. It\'s related to the recent deployment. Fixing now.',
            'Deployed to staging. Can you test and confirm?',
            'Good catch! I\'ll implement this and send a PR.',
            'Approved. Merging to main branch.',
            'This is blocked by task #42. Can we prioritize that first?',
            'Fixed and tested locally. Ready for review.',
            'Waiting on design mockups before proceeding.',
            'Performance improved by 40%. Ready to merge.',
            'All tests passing. Looks good!',
            'Need to update documentation as well.',
            'This will be included in the next release.',
            'Follow-up: also need to handle edge cases.',
            'Assigned to Alice. She\'s the expert on this area.',
            'Should we add this to the backlog for next sprint?',
        ];

        $numComments = rand(1, 3);
        $randomUsers = $users->random($numComments);

        foreach ($randomUsers as $user) {
            Comment::create([
                'body' => $commentTexts[array_rand($commentTexts)],
                'task_id' => $task->id,
                'user_id' => $user->id,
            ]);
        }
    }
}
