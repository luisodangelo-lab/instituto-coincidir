public function up(): void
{
    Schema::create('payments', function (Blueprint $table) {
        $table->id();

        $table->foreignId('user_id')->constrained()->cascadeOnDelete();
        $table->foreignId('enrollment_id')->nullable()->constrained()->nullOnDelete();

        $table->string('type')->default('payment'); // payment | refund
        $table->string('provider')->default('manual'); // manual | mercadopago (futuro)
        $table->string('method')->default('transferencia'); // transferencia | mercadopago | efectivo | otro

        $table->decimal('amount', 12, 2)->default(0);

        $table->string('status')->default('pending_review'); // pending_review | approved | rejected
        $table->string('reference')->nullable(); // referencia/ID MP/alias

        $table->string('receipt_path')->nullable(); // storage/app/public/...
        $table->dateTime('paid_at')->nullable();    // fecha declarada del pago

        // Auditoría:
        $table->foreignId('created_by')->nullable()->references('id')->on('users')->nullOnDelete();
        $table->foreignId('approved_by')->nullable()->references('id')->on('users')->nullOnDelete();
        $table->dateTime('approved_at')->nullable();

        $table->foreignId('refunded_by')->nullable()->references('id')->on('users')->nullOnDelete();
        $table->dateTime('refunded_at')->nullable();

        $table->foreignId('refund_of_payment_id')->nullable()->references('id')->on('payments')->nullOnDelete();

        $table->text('notes')->nullable();

        $table->timestamps();
    });
}
