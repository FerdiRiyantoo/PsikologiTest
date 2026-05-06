<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PapiResult extends Model 
{
    protected $table = 'papi_results';
    protected $fillable = [
        'test_session_id',
        'scale_n','scale_g','scale_a','scale_l','scale_p',
        'scale_i','scale_t','scale_v','scale_x','scale_s',
        'scale_b','scale_o','scale_r','scale_d','scale_c',
        'scale_e','scale_k','scale_f','scale_w','scale_z',
    ];

    public function testSession()
    {
        return $this->belongsTo(TestSession::class, 'test_session_id');
    }

    public function getScalesArray(): array
    {
        return [
            'N' => $this->scale_n,
            'G' => $this->scale_g,
            'A' => $this->scale_a,
            'L' => $this->scale_l,
            'P' => $this->scale_p,
            'I' => $this->scale_i,
            'T' => $this->scale_t,
            'V' => $this->scale_v,
            'X' => $this->scale_x,
            'S' => $this->scale_s,
            'B' => $this->scale_b,
            'O' => $this->scale_o,
            'R' => $this->scale_r,
            'D' => $this->scale_d,
            'C' => $this->scale_c,
            'E' => $this->scale_e,
            'K' => $this->scale_k,
            'F' => $this->scale_f,
            'W' => $this->scale_w,
            'Z' => $this->scale_z,
        ];
    }
    public function getCategorizedScales(): array
    {
        return [
            'Leadership' => [
                'L' => ['score' => $this->scale_l, 'label' => 'Leadership Role', 'desc' => 'Peran – Pemimpin'],
                'P' => ['score' => $this->scale_p, 'label' => 'Need to Control Others', 'desc' => 'Kebutuhan Mengatur Orang Lain'],
                'I' => ['score' => $this->scale_i, 'label' => 'Ease in Decision Making', 'desc' => 'Membuat Keputusan'],
            ],
            'Followership' => [
                'F' => ['score' => $this->scale_f, 'label' => 'Need to Support Authority', 'desc' => 'Kebutuhan Membantu Atasan'],
                'W' => ['score' => $this->scale_w, 'label' => 'Need for Rules and Supervision', 'desc' => 'Kebutuhan Aturan dan Pengawasan'],
            ],
            'Activity' => [
                'T' => ['score' => $this->scale_t, 'label' => 'Pace (Kerja)', 'desc' => 'Kesiapan bekerja, mental, sigap'],
                'V' => ['score' => $this->scale_v, 'label' => 'Vigorous Type', 'desc' => 'Peran Penuh Semangat – Fisik'],
            ],
            'Work Style' => [
                'R' => ['score' => $this->scale_r, 'label' => 'Theoretical Type', 'desc' => 'Peran Orang yang Teoritis'],
                'D' => ['score' => $this->scale_d, 'label' => 'Interest in Working With Details', 'desc' => 'Peran Bekerja dengan Hal Rinci'],
                'C' => ['score' => $this->scale_c, 'label' => 'Organized Type', 'desc' => 'Peran Mengatur'],
            ],
            'Social Nature' => [
                'X' => ['score' => $this->scale_x, 'label' => 'Need to be Noticed', 'desc' => 'Kebutuhan untuk Diperhatikan'],
                'B' => ['score' => $this->scale_b, 'label' => 'Need to Belong to Groups', 'desc' => 'Diterima dalam Kelompok'],
                'O' => ['score' => $this->scale_o, 'label' => 'Need for Closeness and Affection', 'desc' => 'Kebutuhan Kedekatan dan Kasih Sayang'],
                'S' => ['score' => $this->scale_s, 'label' => 'Social Extension', 'desc' => 'Peran Hubungan Sosial'],
            ],
            'Work Direction' => [
                'N' => ['score' => $this->scale_n, 'label' => 'Need to Finish Task', 'desc' => 'Menyelesaikan Tugas Secara Mandiri'],
                'G' => ['score' => $this->scale_g, 'label' => 'Role of Hard Intense Worker', 'desc' => 'Peran Pekerja Keras'],
                'A' => ['score' => $this->scale_a, 'label' => 'Need for Achievement', 'desc' => 'Kebutuhan Berprestasi'],
            ],
            'Temperament' => [
                'Z' => ['score' => $this->scale_z, 'label' => 'Need for Change', 'desc' => 'Kebutuhan untuk Berubah'],
                'K' => ['score' => $this->scale_k, 'label' => 'Need to be Forceful', 'desc' => 'Kebutuhan untuk Agresif'],
                'E' => ['score' => $this->scale_e, 'label' => 'Emotional Resistant', 'desc' => 'Peran Pengendalian Emosi'],
            ],
        ];
    }

    public function getScoreInterpretation(string $scale, int $score): string
    {
        $scale = strtoupper($scale);

        $interpretations = [
            'N' => [
                [0,2, 'Menunda atau menghindari pekerjaan'],
                [3,4, 'Berhati-hati/ragu dalam bekerja'],
                [5,6, 'Cukup bertanggung jawab pada pekerjaan'],
                [7,9, 'Tekun, tanggung jawab tinggi'],
            ],
            'G' => [
                [3,4, 'Bekerja untuk kesenangan saja, bukan hasil optimal'],
                [5,9, 'Kemauan bekerja keras tinggi'],
            ],
            'A' => [
                [0,5, 'Ketidakpastian tujuan, tidak ada usaha lebih'],
                [6,9, 'Tujuan jelas, kebutuhan untuk sukses dan ambisi tinggi'],
            ],
            'L' => [
                [0,4, 'Cenderung kurang nyaman memimpin memimpin'],
                [5,9, 'Role memimpin yang baik'],
            ],
            'P' => [
                [0,4, 'Kurang mampu menerima tanggung jawab atas orang lain (mengatur)'],
                [5,9, 'Mampu menerima tanggung jawab atas orang lain (mengatur)'],
            ],
            'I' => [
                [0,2, 'Ragu/menolak mengambil keputusan'],
                [3,4, 'Berhati-hati dalam memilih mengambil keputusan'],
                [5,7, 'Mampu mengambil keputusan tetapi masih hati-hati'],
                [8,9, 'Tidak ragu dalam mengambil keputusan'],
            ],
            'T' => [
                [0,3, 'Melakukan pekerjaan sesuai kemauan sendiri'],
                [4,6, 'Kesiapan bekerja, mental dalam bekerja, sigap, paham urgensi'],
            ],
            'V' => [
                [0,4, 'Cenderung pasif'],
                [5,7, 'Aktif dan sportif'],
            ],
            'X' => [
                [0,1, 'Pemalu'],
                [2,3, 'Rendah hati, tulus'],
                [4,5, 'Memiliki pola perilaku unik'],
                [6,9, 'Membutuhkan perhatian nyata'],
            ],
            'S' => [
                [0,5, 'Perhatian rendah terhadap hubungan sosial, kurang percaya pada orang lain'],
                [6,9, 'Kepercayaan diri terhadap hubungan sosial, suka interaksi'],
            ],
            'B' => [
                [0,3, 'Selektif'],
                [4,5, 'Butuh diterima, tidak mudah dipengaruhi kelompok'],
                [6,9, 'Butuh disukai dan diakui, mudah dipengaruhi'],
            ],
            'O' => [
                [0,2, 'Tidak suka hubungan perorangan'],
                [3,4, 'Sadar akan hubungan perorangan tetapi tidak terlalu tergantung'],
                [5,9, 'Sangat tergantung, butuh penerimaan diri'],
            ],
            'R' => [
                [0,4, 'Kurang perhatian, bersifat praktis'],
                [5,9, 'Memiliki nilai penalaran tinggi'],
            ],
            'D' => [
                [0,3, 'Tidak minat bekerja secara detail'],
                [4,9, 'Minat tinggi bekerja secara detail'],
            ],
            'C' => [
                [0,2, 'Fleksibel, tidak teratur'],
                [3,5, 'Teratur tetapi tidak fleksibel'],
                [6,9, 'Keteraturan tinggi, cenderug kaku'],
            ],
            'Z' => [
                [0,2, 'Tidak suka berubah'],
                [3,4, 'Tidak suka perubahan jika dipaksakan'],
                [5,6, 'Mudah menyesuaikan diri'],
                [6,7, 'Membuat perubahan yang selektif, berfikir jauh kedepan'],
                [8,9, 'Cemas jika tidak ada perubahan yang fantastis'],
            ],
            'E' => [
                [0,1, 'Terbuka, cepat bereaksi'],
                [2,3, 'Terbuka'],
                [4,6, 'Punya kedekatan emosional seimbang, mampu mengendalikan'],
                [7,9, 'Sangat normatif, pengendalian diri yag berlebihan'],
            ],
            'K' => [
                [0,2, 'Menghindari masalah'],
                [3,4, 'Suka lingkungan tenang, menghindari konflik'],
                [6,7, 'Agresi berhubungan dengan kerja, dorongan semangat bersaing'],
                [8,9, 'Agresif, cenderung defensive'],
            ],
            'F' => [
                [0,1, 'Egois, ada kemungkinan memberontak'],
                [2,3, 'Mengurus kepentingan sendiri'],
                [4,5, 'Setia terhadap perusahaan'],
                [6,9, 'Setia dan membantu, aktif memberikan saran'],
            ],
            'W' => [
                [0,3, 'Mandiri, orientasi pada tujuan sendiri'],
                [4,5, 'Kebutuhan adanya arahan yang diberikan untuknya'],
                [6,9, 'Membutuhkan instruksi yang jelas'],
            ],
        ];

        if (!isset($interpretations[$scale])) {
            return '-';
        }

        foreach ($interpretations[$scale] as [$min, $max, $desc]) {
            if ($score >= $min && $score <= $max) {
                return $desc;
            }
        }

        return '-';
    }

    
}