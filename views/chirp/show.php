<?php

/** @var App\Models\Chirp $chirp */

?>

<?php include __DIR__ . '/../_head.php' ?>

<section>
    <?php if ($chirp->parent): ?>
        <blockquote>
            <article>
                <header>
                    <?= e($chirp->parent->user->name) ?> at <time datetime="<?= e($chirp->parent->created_at) ?>"><?= e($chirp->parent->created_at) ?></time>
                </header>
                <p>
                    <?= e($chirp->parent->content) ?>
                </p>
                <footer>
                    <?= link_to($chirp->parent, 'View') ?>
                </footer>
            </article>
        </blockquote>
    <?php endif ?>

    <article>
        <header>
            <?= e($chirp->user->name) ?> at <time datetime="<?= e($chirp->created_at) ?>"><?= e($chirp->created_at) ?></time>
        </header>
        <section>
            <?= e($chirp->content) ?>
        </section>
        <footer>
            <?= delete_button($chirp->getDeletePath()) ?>
        </footer>
    </article>
</section>

<section>
    <form action="/chirps/create" method="POST">
        <!-- TODO: Add CSRF token! -->

        <input type="hidden" name="chirp_id" value="<?= e($chirp->id) ?>">

        <p>
            <textarea name="content" id="content" rows="6" placeholder="What's on your mind?"
                      aria-label="What's on your mind?" style="width: 100%"></textarea>

            <span style="color: red"><?= e(errors('content')) ?></span>
        </p>

        <p style="text-align: right">
            <button type="submit">Reply</button>
        </p>
    </form>

    <?php foreach ($chirp->replies ?? [] as $reply): ?>
        <article>
            <header>
                <?= e($reply->user->name) ?> at <time datetime="<?= e($reply->created_at) ?>"><?= e($reply->created_at) ?></time>
            </header>
            <p>
                <?= e($reply->content) ?>
            </p>
            <footer>
                <span><?= $reply->getReplyCount() ?> Replies</span>&nbsp;
                <?= link_to($reply, 'View') ?>
            </footer>
        </article>
    <?php endforeach ?>
</section>

<?php include __DIR__ . '/../_tail.php' ?>