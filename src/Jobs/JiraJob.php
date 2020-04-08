<?php

namespace Helldar\Notifex\Jobs;

use Helldar\Notifex\Abstracts\JobAbstract;
use Illuminate\Support\Facades\Config;
use JiraRestApi\Configuration\ArrayConfiguration;
use JiraRestApi\Issue\IssueField;
use JiraRestApi\Issue\IssueService;

class JiraJob extends JobAbstract
{
    /**
     * @throws \JsonMapper_Exception
     * @throws \Exception
     */
    public function handle()
    {
        $field   = new IssueField();
        $service = new IssueService($this->getJiraConfiguration());

        $field
            ->setProjectKey($this->config('project_key'))
            ->setIssueType($this->config('issue_type'))
            ->setPriorityName($this->config('priority_name'))
            ->setSummary($this->title())
            ->setDescription($this->description())
            ->addLabel($this->host())
            ->addLabel(Config::get('app.env'))
            ->addLabel(class_basename($this->classname));

        $service->create($field);
    }

    private function title(): string
    {
        $environment = Config::get('app.env');

        return sprintf('%s | %s | %s', $environment, $this->host(), class_basename($this->classname));
    }

    private function description(): string
    {
        return implode(PHP_EOL, [
            sprintf('*%s*', $this->message),
            sprintf('_%s:%s_', $this->file, $this->line),
            sprintf('{code:bash}%s{code}', $this->trace_as_string),
        ]);
    }

    private function host(): string
    {
        $url = app('request')->url() ?? Config::get('app.url') ?? 'http://localhost';

        return parse_url($url, PHP_URL_HOST);
    }

    private function getJiraConfiguration(): ArrayConfiguration
    {
        return new ArrayConfiguration([
            'jiraHost'     => $this->config('host'),
            'jiraUser'     => $this->config('user'),
            'jiraPassword' => $this->config('password'),
        ]);
    }

    private function config(string $key)
    {
        return $this->getConfig(get_class(), $key);
    }
}
