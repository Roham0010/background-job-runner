# Laravel Custom Background Job Runner System Documentation

### Features:
- Dynamic execution of background jobs using class name, method name, and parameters.
- Configurable retry attempts and delays.
- Job logging to track execution status and errors.
- Sample random error throwing in jobs
- Error handling with retries and delays.
- (Optional) I did not do the dashboard part.

---

### The folder structure is as follow:
- **Contracts**
    - `JobInterface.php`
- **Factories**
    - `JobFactory.php`
- **Jobs**
    - `Invoice/CompleteInvoiceJob.php`
    - `Email/SendEmailJob.php`
- **Abstracts**
    - `JobAbstract.php`
- **Services**
	- `BackgroundJobRunner.php` (Main runner class)
- **config/background_jobs.php**
- **app/helpers.php**
- **logs/background_jobs.log**


### Usage
- Configure the project as below in the next steps if needed
- Run `composer install` to install the packages
- Setup the mysql in case want to run commands via the provided route
- Run `php artisan key:generate` and `php artisan migrate` if needed
- Start the project with `php artisan serve`
- With commands:
	- run these commands to execute jobs:
	```
		job:job-fail 		// Failing sample
		job:job-invoice		// Invoice sample
		job:job-success		// Email success sample(may also include the random error and retrying)
	```
- With route `localhost:8000/` you can run a simple Email job


### To configure, add and update the jobs you can go to the background_jobs config file and edit:
```php
// config/background_jobs.php
return [
    'retry_attempts' => 3,
    'retry_delay' => 5, // Delay in seconds between retries
    'jobs' => [
        'Invoice' => [
            'job_class' => 'CompleteInvoiceJob',
            'retry_attempts' => 3,
            'retry_delay' => 10,
        ],
        'Email' => [
            'job_class' => 'SendEmailJob',
            'retry_attempts' => 5,
            'retry_delay' => 5,
        ],
    ],
];
```

### Retry Attempts & Delays
In the configuration file `background_jobs.php`, you can set the retry attempts and the delay between retries.
The root ones are in case we have some jobs that follow the same numbers as others. they are defaults.

- **retry_attempts**: Number of attempts to retry the job if it fails.
- **retry_delay**: The time in seconds to wait before retrying the job.

For example:
```php
'retry_attempts' => 3, // Retry 3 times before failing
'retry_delay' => 10, // Wait 10 seconds between retries
```

### Security Settings
- To ensure only approved jobs are run, we validate job class names and method names to avoid running arbitrary or harmful code.
- We also validate the method parameters to ensure the naming and quantity.

---

## Testing & Logs

### Sample Usage
Here is an example of testing the background job runner manually:

```php
// Simulate running an email job
$params = ['email' => "email@gmail.com", 'subject' => 'sbj', 'message' => 'msg'];
runBackgroundJob('Email', 'sendEmail', $params);
```
The class name is what it is in the config/background_jobs.jobs array keys.

### Sample Log Output

#### background_jobs.log
Logs are also added to the repo.
We have two types of logging that can be changed manually and also with $logger->setOutputType(json|string)
```plaintext

[2024-11-12 18:19:01] local.INFO: Email -> RUNNING <===============>
[2024-11-12 18:19:01] local.INFO: Email -> ATTEMPT: 0
[2024-11-12 18:19:01] local.INFO: Email -> FROM_JOB_CLASS: {Sending email to: email@gmail.com, Subject: sbj}
[2024-11-12 18:19:01] local.INFO: Email -> FROM_JOB_CLASS: {Email sent successfully to: email@gmail.com}
[2024-11-12 18:19:01] local.INFO: Email -> SUCCEED <===============>
[2024-11-12 18:19:18] local.INFO: Invoice -> RUNNING <===============>
[2024-11-12 18:19:18] local.INFO: Invoice -> ATTEMPT: 0
[2024-11-12 18:19:18] local.INFO: Invoice -> FROM_JOB_CLASS: {starting invoice completion}
[2024-11-12 18:19:18] local.ERROR: Invoice -> Sample ERROR
[2024-11-12 18:19:18] local.INFO: Invoice -> FAILED attempt #1
[2024-11-12 18:19:18] local.ERROR: Invoice -> Sample ERROR
[2024-11-12 18:19:28] local.INFO: Invoice -> ATTEMPT: 1
[2024-11-12 18:19:28] local.INFO: Invoice -> FROM_JOB_CLASS: {starting invoice completion}
[2024-11-12 18:19:28] local.INFO: Invoice -> FROM_JOB_CLASS: {invoice completed: 123}
[2024-11-12 18:19:28] local.INFO: Invoice -> SUCCEED <===============>

||


[2024-11-12 18:28:24] local.INFO: array (
  'job_name' => 'Email',
  'status' => 'RUNNING <===============>',
  'log_type' => 'info',
  'additional_info' =>
  array (
  ),
  'timestamp' => '2024-11-12 18:28:24',
)
[2024-11-12 18:28:24] local.INFO: array (
  'job_name' => 'Email',
  'status' => 'ATTEMPT: 0',
  'log_type' => 'info',
  'additional_info' =>
  array (
  ),
  'timestamp' => '2024-11-12 18:28:24',
)
```

 ## How to add new jobs
 - Defind a new item in the config file
 - Create the job in BackgroundJubRunnerService/Jobs and extend it from the JobAbastract to have the logging functionality
 - Define the method name it should execute
 - Run the job with the runBackgroundJob helper

## Limitations & Improvements

### Assumptions
- From assessment: [Create a PHP script that can run classes or methods] I consider the "or methods" doesn't refer to also running stand alone methods, in a realworld project I would ask that before implementation.
- The job classes extend the `JobAbstract`.
- Jobs that are dynamically created will follow the naming convention of `ClassNameJob`.
- I used sleep for delays for just simplicity, I could have used the dispatch too but you asked for not using Laravel system, or could have used a table to cronjobs to watch for failed jobs etc as Laravel Horizon does...

### Limitations
- The system does not support complex job dependency chains (i.e., waiting for one job to complete before another starts).
- Job priority handling is not implemented.
