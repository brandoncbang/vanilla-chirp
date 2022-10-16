<?php

/** @var \App\Models\Chirp[] $chirps */

?>

<?php include __DIR__ . '/../_head.php' ?>

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
</section>

<?php if (!empty($chirps)): ?>
    <section>
        <?php foreach ($chirps as $chirp): ?>
            <article>
                <header>
                    <?= e($chirp->user->name) ?> at <time datetime="<?= e($chirp->created_at) ?>"><?= e($chirp->created_at) ?></time>
                </header>
                <section>
                    <?= e($chirp->content) ?>
                </section>
                <footer>
                    <?= link_to($chirp, 'View') ?>&nbsp;
                    <?= delete_button($chirp->getDeletePath()) ?>
                </footer>
            </article>
        <?php endforeach ?>
    </section>
<?php else: ?>
    <p>Check back soon, or post your own Chirps!</p>
<?php endif ?>

<?php include __DIR__ . '/../_tail.php' ?>