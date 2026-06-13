<?php

use App\Models\Portofolio;

$p = Portofolio::where('judul', 'Uji Bukti Tautan E2E')->latest('portofolio_id')->first();

echo 'portofolio_id: '.$p->portofolio_id.PHP_EOL;
echo 'jumlah bukti: '.$p->bukti->count().PHP_EOL;

foreach ($p->bukti as $b) {
    echo '- sumber='.$b->sumber
        .' | nama='.$b->nama_file
        .' | url='.$b->url
        .' | path='.($b->path_file ?? 'NULL')
        .' | sematan='.($b->urlSematan() ?? 'tidak')
        .' | layanan='.$b->layanan().PHP_EOL;
}
