<?php

namespace Helldar\NotifyExceptions\Jobs;

use Helldar\NotifyExceptions\Models\ErrorNotification;
use Helldar\NotifyExceptions\Services\SlackService;
use Helldar\NotifyExceptions\Traits\Titles;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use JiraRestApi\Issue\IssueField;
use JiraRestApi\Issue\IssueService;

class JiraJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, Notifiable, Titles;

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
        $field   = new IssueField;
        $service = new IssueService;

        $field
            ->setProjectKey(config('notifex.jira.project_key'))
            ->setIssueType(config('notifex.jira.issue_type'))
            ->setPriorityName(config('notifex.jira.priority_name'))
            ->setSummary($this->title())
            ->setDescription($this->getDescription())
            ->addLabel(config('app.url'))
            ->addLabel(config('app.env'))
            ->addLabel($this->item->parent);
    }

    private function getDescription(): string
    {
        return implode(PHP_EOL, [
            sprintf('Message: *%s*', $this->item->exception->getMessage()),
            sprintf('File: *%s:%s*', $this->item->exception->getFile(), $this->item->exception->getLine()),
            sprintf('{code:bash}%s{code}', $this->item->exception->getTraceAsString()),
        ]);
    }
}
