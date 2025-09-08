  <!-- TESTIMONIOS (estáticos de ejemplo) -->
  <section class="py-16 bg-slate-900/50 border-y border-white/10">
    <div class="max-w-6xl mx-auto px-4">
      <h2 class="text-3xl md:text-4xl font-bold">Lo que dice la comunidad</h2>
      <div class="grid md:grid-cols-3 gap-6 mt-8">
        <?php
          $quotes = [
            ['Dev Junior', '“Me aprendí los estados HTTP en el gym. ¡Épico!”'],
            ['Mentor', '“Excelente recurso para repasar fundamentos en clase.”'],
            ['Recruiter', '“Ideal para mantener la mente técnica mientras trabajas.”'],
          ];
          foreach ($quotes as $q):
        ?>
          <figure class="p-6 rounded-xl bg-white/5 border border-white/10">
            <blockquote class="text-slate-200"><?= e($q[1]) ?></blockquote>
            <figcaption class="mt-3 text-sm text-slate-400">— <?= e($q[0]) ?></figcaption>
          </figure>
        <?php endforeach; ?>
      </div>
    </div>
  </section>