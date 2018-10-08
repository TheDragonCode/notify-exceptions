<?php

namespace Helldar\NotifyExceptions\Jobs;

use Helldar\NotifyExceptions\Models\ErrorNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use JiraRestApi\Configuration\ArrayConfiguration;
use JiraRestApi\Issue\IssueField;
use JiraRestApi\Issue\IssueService;
use JiraRestApi\JiraException;

class JiraJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, Notifiable;

    protected $item;

    public function __construct(ErrorNotification $item)
    {
        $this->item = $item;
    }

    public function handle()
    {
        $this->toJira();
    }

    private function toJira()
    {
        try {
            $field   = new IssueField;
            $service = new IssueService($this->getJiraConfiguration());

            $field
                ->setProjectKey(config('notifex.jira.project_key'))
                ->setIssueType(config('notifex.jira.issue_type'))
                ->setPriorityName(config('notifex.jira.priority_name'))
                ->setSummary($this->getTitle())
                ->setDescription('test')
                ->setDescription($this->getDescription())
                ->addLabel(config('app.url'))
                ->addLabel(config('app.env'))
                ->addLabel($this->item->parent);

            $service->create($field);
        } catch (JiraException $exception) {
            app('sneaker')->captureException($exception);
        }
    }

    private function getTitle(): string
    {
        $server      = request()->getHost() ?? config('app.url');
        $environment = config('app.env');

        return sprintf('%s | Server - %s | Environment - %s', $this->item->parent, $server, $environment);
    }

    private function getDescription(): string
    {
        return implode(PHP_EOL, [
            sprintf('*%s*', $this->item->exception->getMessage()),
            sprintf('_%s:%s_', $this->item->exception->getFile(), $this->item->exception->getLine()),
            sprintf('{code:bash}%s{code}', $this->item->exception->getTraceAsString()),
        ]);
    }

    private function getJiraConfiguration(): ArrayConfiguration
    {
        return new ArrayConfiguration([
            'jiraHost'     => config('notifex.jira.host'),
            'jiraUser'     => config('notifex.jira.user'),
            'jiraPassword' => config('notifex.jira.password'),
        ]);
    }
}
