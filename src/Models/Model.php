<?php

namespace SRC\Models;

class Model
{
    private $conn;
    private $page;
    private $limit;

    private $response;

    public function __construct($conn, $data = null, $page, $limit)
    {
        $this->conn = $conn->connect();
        $this->page = $page;
        $this->limit = $limit;

        $cnpj = Validations::validationHash($data['hash']);

        if($cnpj != false) {
            $this->response = $this->listCnpj($cnpj);
        } else {
            header('HTTP/1.1 401 Unauthorized');
            $this->response = json_encode(array("response" => "Erro de autenticação"));
        }
    }

    private function listCnpj($cnpj)
    {
        $data = array();
        $records = '';
        $i = 0;

        //Valores fixo
        $fixed = array(
            'custoMedio' => 10.00,
            'margem' => 5.00,
            'linha' => 1,
            'grupoDesc' => 'nova'
        );

        if ($cnpj == 'MARKETPLACE') {
            $query = (
                'SELECT procod_est, pronom_uni, proemp_est, proprt_est, provlr_est, prolin_uni, procmc_est, prodes_uni,
                (proeat_est - proofc_est - provnd_est - proout_est) AS estoque
                FROM tb_pcapro_est
                INNER JOIN tb_pcapro_uni
                ON procod_uni = procod_est WHERE EXISTS (SELECT 1 from tb_transac, tb_trajuridi  
                WHERE tra_acesso=proemp_est AND tra_sequence=tju_codigo) ORDER BY proemp_est, procod_est'
            );
        } else {
            $query = (
                'SELECT procod_est, pronom_uni, proemp_est, proprt_est, provlr_est, prolin_uni, procmc_est, prodes_uni,
                (proeat_est - proofc_est - provnd_est - proout_est) AS estoque
                FROM tb_pcapro_est
                INNER JOIN tb_pcapro_uni
                ON procod_uni = procod_est WHERE EXISTS (SELECT 1 from tb_transac, tb_trajuridi  
                WHERE tra_acesso=proemp_est AND tra_sequence=tju_codigo AND tju_cnpj = '.$cnpj.')'
            );
        }

        $result = $this->conn->PageExecute($query, $this->limit, $this->page);

        $records = $result->MaxRecordCount();

        while (!$result->EOF) {
            for ($k=0; $k < 9; $k++) {
                switch ($k) {
                    case 0:
                        $data[$i]['codigo'] = Validations::removeSpaces($result->fields[$k]);
                    break;
                    case 1:
                        $data[$i]['descricao'] = Validations::removeSpaces($result->fields[$k]);
                    break;
                    case 2:
                        $data[$i]['empresa'] = Validations::removeSpaces($result->fields[$k]);
                    break;
                    case 3:
                        $data[$i]['prateleira'] = Validations::removeSpaces($result->fields[$k]);
                    break;
                    case 4:
                        $data[$i]['valor'] = Validations::removeSpaces(number_format($result->fields[$k], 2, ',', '.'));
                    break;
                    case 5:
                        $data[$i]['linha'] = Validations::validationEstock($result->fields[$k]);
                    break;
                    case 6:
                        $data[$i]['custoMedio'] = Validations::validationEstock($result->fields[$k]);
                    break;
                    case 7:
                        $data[$i]['grupoDesc'] = Validations::validationEstock($result->fields[$k]);
                    break;
                    case 8:
                        $data[$i]['estoque'] = Validations::validationEstock($result->fields[$k]);
                    break;
                }
                $data[$i]['margem'] = $fixed['margem'];
            }
            $result->MoveNext();
            $i++;
        }
        $data = (!empty($data) ? $data : null);

        $dataResponse = array(
            'headers' => array(
                'records_total' => $records,
                'records_on_page' => $result->RecordCount(),
                'last_page' => $result->LastPageNo(),
                'current_page' => $this->page
            ),
            'data' => $data
        );
        // json_encode(array("response" => $dataResponse));
        return json_encode(array("response" => $dataResponse));
    }

    public function getRespose()
    {
        return $this->response;
    }
}
