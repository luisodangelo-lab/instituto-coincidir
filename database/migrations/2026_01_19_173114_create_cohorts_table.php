public function up(): void
{
    Schema::create('cohorts', function (Blueprint $table) {
        $table->id();
        $table->foreignId('course_id')->constrained()->cascadeOnDelete();

        $table->string('name'); // ej: "Marzo 2026", "Cohorte 1", etc.
        $table->date('start_date')->nullable();
        $table->date('end_date')->nullable();

        // Precio y plan sugerido (1..10 cuotas)
        $table->decimal('price_total', 12, 2)->default(0);
        $table->unsignedTinyInteger('installments_count')->default(1); // 1..10 (cursos cortos 1-5 / tecnicatura 10)

        $table->boolean('is_active')->default(true);

        $table->timestamps();

        $table->unique(['course_id', 'name']);
    });
}
