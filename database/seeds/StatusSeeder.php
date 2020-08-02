<?php

use Illuminate\Database\Seeder;

class StatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach (['Pendente', 'Em preparo', 'Em entrega', 'Entregue', 'Cancelado'] as $key => $status) {
            App\Status::create([
                'id' => $key+1,
                'descricao' => $status
            ]);
        }
    }
}
