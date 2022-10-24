<?php

/** @var App\Models\Chirp $chirp */

?>

<?php include __DIR__ . '/../_head.php' ?>

<h1>View Chirp</h1>
<p>
    <a href="/">&larr; Back to timeline</a>
</p>

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
        <p>
            <?= e($chirp->content) ?>
        </p>
        <footer>
            <?php if ($chirp->user->id === current_user()->id): ?>
                <?= delete_button($chirp->getDeletePath()) ?>
            <?php else: ?>
                <?= button_to('/likes', 'Like', ['chirp_id' => $chirp->id]) ?>
            <?php endif ?>
        </footer>
    </article>
</section>

<section>
    <h2>Replies</h2>

    <form action="/chirps/create" method="POST">
        <!-- TODO: Add CSRF token! -->

        <input type="hidden" name="chirp_id" value="<?= e($chirp->id) ?>">

        <p>
            <textarea name="content" id="content" rows="2" placeholder="Chirp your reply"
                      aria-label="Chirp your reply" style="width: 100%"></textarea>

            <span style="color: red"><?= e(errors('content')) ?></span>
        </p>

        <p style="text-align: right">
            <button type="submit">Reply</button>
        </p>
    </form>
    <br>

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