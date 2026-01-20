public function up(): void
{
    Schema::create('courses', function (Blueprint $table) {
        $table->id();
        $table->string('code')->unique();           // ej: TEC-ADM-2026, CUR-EXCEL-01
        $table->string('title');
        $table->text('description')->nullable();

        $table->string('type')->default('curso');   // curso | tecnicatura
        $table->boolean('is_active')->default(true);

        $table->timestamps();
    });
}
