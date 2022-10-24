<?php

/** @var \App\Models\Chirp[] $chirps */

?>

<?php include __DIR__ . '/../_head.php' ?>

<h1>Timeline</h1>

<section>
    <form action="/chirps/create" method="POST">
        <!-- TODO: Add CSRF token! -->

        <p>
            <textarea name="content" id="content" rows="6" placeholder="What's on your mind?"
                      aria-label="What's on your mind?" style="width: 100%"></textarea>

            <span style="color: red"><?= e(errors('content')) ?></span>
        </p>

        <p style="text-align: right">
            <button type="submit">Chirp!</button>
        </p>
    </form>
    <br>

    <?php if (!empty($chirps)): ?>
        <?php foreach ($chirps as $chirp): ?>
            <article>
                <header>
                    <?= e($chirp->user->name) ?> at <time datetime="<?= e($chirp->created_at) ?>"><?= e($chirp->created_at) ?></time>
                </header>
                <p>
                    <?= e($chirp->content) ?>
                </p>
                <footer>
                    <span><?= $chirp->getReplyCount() ?> Replies</span>&nbsp;
                    <?= link_to($chirp, 'View') ?>
                </footer>
            </article>
        <?php endforeach ?>
    <?php else: ?>
        <p>Check back soon, or post your own Chirps!</p>
    <?php endif ?>
</section>

<?php include __DIR__ . '/../_tail.php' ?>