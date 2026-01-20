public function up(): void
{
    Schema::create('enrollments', function (Blueprint $table) {
        $table->id();
        $table->foreignId('user_id')->constrained()->cascadeOnDelete();
        $table->foreignId('cohort_id')->constrained()->cascadeOnDelete();

        $table->string('status')->default('inscripto'); // inscripto | cursando | aprobado | baja
        $table->dateTime('enrolled_at')->nullable();

        $table->timestamps();

        $table->unique(['user_id', 'cohort_id']);
    });
}
