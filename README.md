# Batch experiment with Laravel

The purpose of this repo is to prove that you can add to a queue batch.

What will happen is:

1. Send a batch of two to the queue.
2. Once executed the `then` closure will be executed.
3. If less than 3 jobs have been executed, another is added to the batch.

## Setup

Setup the .env file

```dotenv
DB_CONNECTION=sqlite

QUEUE_CONNECTION=database
```

Migrate a SQLite DB.

```shell
php artisan migrate
```

Then run the queue worker in one terminal

```shell
php artisan queue:work
```

Then run the following to fire off the batch

```shell
php artisan qt
```

You should see the results in Ray.

## Conclusion

1. Laravel batches can be added to from Then closure.
2. Laravel batches reset the `finished_at` value when adding new
jobs to the batch from the Then closure. This happens in the
`\Illuminate\Bus\DatabaseBatchRepository`.
3. This can be dangerous if the Then closure can end up in a loop 
adding additional jobs.
4. One downside is that the `finished_at` is saved before the addition 
of any new jobs making it potentially seem like the batch is complete.
Instead, there needs to be a secondary notice somewhere. 
