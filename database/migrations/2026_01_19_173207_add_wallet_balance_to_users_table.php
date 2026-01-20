public function up(): void
{
    Schema::create('wallet_movements', function (Blueprint $table) {
        $table->id();

        $table->foreignId('user_id')->constrained()->cascadeOnDelete();
        $table->foreignId('payment_id')->nullable()->references('id')->on('payments')->nullOnDelete();

        $table->decimal('amount', 12, 2); // + crédito, - débito
        $table->string('reason');         // overpay | manual_credit | refund_adjust | etc
        $table->text('notes')->nullable();

        $table->foreignId('created_by')->nullable()->references('id')->on('users')->nullOnDelete();

        $table->timestamps();
    });
}
