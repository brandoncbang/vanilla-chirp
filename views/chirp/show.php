<?php

/** @var App\Models\Chirp $chirp */

?>

<?php include __DIR__ . '/../_head.php' ?>

<section>
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

    <article>

    </article>
</section>

<?php include __DIR__ . '/../_tail.php' ?>