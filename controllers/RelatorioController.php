<?php
require_once 'models/PlanoEnsinoModel.php';
require_once 'vendor/autoload.php'; // Certifique-se de instalar as dependências via Composer

use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\Style\Font;
use Dompdf\Dompdf;

class RelatorioController {
    private $planoEnsinoModel;
    
    public function __construct() {
        $this->planoEnsinoModel = new PlanoEnsinoModel();
    }
    
    // Página principal de relatórios
    public function index() {
        // Obter planos aprovados para exportação
        $planosAprovados = $this->planoEnsinoModel->getPlanosByStatus('aprovado');
        
        $data = [
            'title' => 'Relatórios',
            'planosAprovados' => $planosAprovados
        ];
        
        $this->renderView('relatorios/index', $data);
    }
    
    // Exportar para Word
    public function exportarWord() {
        if (!isset($_GET['id'])) {
            $_SESSION['mensagem'] = 'ID do plano não fornecido';
            header('Location: index.php?page=relatorios');
            exit;
        }
        
        $id = (int)$_GET['id'];
        $plano = $this->planoEnsinoModel->getById($id);
        
        if (!$plano || $plano['status'] !== 'aprovado') {
            $_SESSION['mensagem'] = 'Plano não encontrado ou não está aprovado';
            header('Location: index.php?page=relatorios');
            exit;
        }
        
        // Criar documento Word
        $phpWord = new PhpWord();
        $section = $phpWord->addSection();
        
        // Estilos
        $phpWord->addTitleStyle(1, ['bold' => true, 'size' => 16], ['align' => 'center']);
        $phpWord->addTitleStyle(2, ['bold' => true, 'size' => 14], ['spaceAfter' => 120]);
        
        // Cabeçalho
        $section->addTitle("PLANO DE ENSINO", 1);
        $section->addTextBreak(1);
        
        // Informações do plano
        $section->addTitle("1. Identificação", 2);
        $table = $section->addTable(['borderSize' => 1, 'borderColor' => '000000']);
        
        // Adicionar linhas à tabela
        $this->addTableRow($table, 'Disciplina:', $plano['disciplina_nome'] . ' - ' . $plano['disciplina_codigo']);
        $this->addTableRow($table, 'Curso:', $plano['curso_nome']);
        $this->addTableRow($table, 'Professor:', $plano['professor_nome']);
        $this->addTableRow($table, 'Semestre:', $plano['semestre'] . '/' . $plano['ano']);
        
        $section->addTextBreak(1);
        
        // Objetivos
        $section->addTitle("2. Objetivos", 2);
        $section->addText("2.1 Objetivo Geral:", ['bold' => true]);
        $section->addText(htmlspecialchars($plano['objetivos_gerais']));
        $section->addTextBreak(1);
        
        $section->addText("2.2 Objetivos Específicos:", ['bold' => true]);
        $section->addText(htmlspecialchars($plano['objetivos_especificos']));
        $section->addTextBreak(1);
        
        // Metodologia
        $section->addTitle("3. Metodologia", 2);
        $section->addText(htmlspecialchars($plano['metodologia']));
        $section->addTextBreak(1);
        
        // Recursos didáticos
        $section->addTitle("4. Recursos Didáticos", 2);
        $section->addText(htmlspecialchars($plano['recursos_didaticos']));
        $section->addTextBreak(1);
        
        // Avaliação
        $section->addTitle("5. Avaliação", 2);
        $section->addText(htmlspecialchars($plano['avaliacao']));
        $section->addTextBreak(1);
        
        // Bibliografia
        $section->addTitle("6. Bibliografia", 2);
        $section->addText("6.1 Bibliografia Básica:", ['bold' => true]);
        $section->addText(htmlspecialchars($plano['bibliografia_basica']));
        $section->addTextBreak(1);
        
        $section->addText("6.2 Bibliografia Complementar:", ['bold' => true]);
        $section->addText(htmlspecialchars($plano['bibliografia_complementar']));
        $section->addTextBreak(1);
        
        // Cronograma
        $section->addTitle("7. Cronograma", 2);
        $section->addText(htmlspecialchars($plano['cronograma_detalhado']));
        
        // Observações
        if (!empty($plano['observacoes'])) {
            $section->addTextBreak(1);
            $section->addTitle("8. Observações", 2);
            $section->addText(htmlspecialchars($plano['observacoes']));
        }
        
        // Informações de aprovação
        $section->addTextBreak(2);
        $section->addText("Plano aprovado em: " . date('d/m/Y', strtotime($plano['data_aprovacao'])));
        
        // Salvar o documento temporariamente
        $filename = 'Plano_Ensino_' . $plano['disciplina_codigo'] . '_' . $plano['semestre'] . '_' . $plano['ano'] . '.docx';
        $tempFile = tempnam(sys_get_temp_dir(), 'plano');
        $objWriter = IOFactory::createWriter($phpWord, 'Word2007');
        $objWriter->save($tempFile);
        
        // Enviar o arquivo para download
        header('Content-Description: File Transfer');
        header('Content-Type: application/vnd.openxmlformats-officedocument.wordprocessingml.document');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        header('Content-Length: ' . filesize($tempFile));
        header('Pragma: public');
        
        readfile($tempFile);
        unlink($tempFile); // Remover arquivo temporário
        exit;
    }
    
    // Exportar para PDF
    public function exportarPdf() {
        if (!isset($_GET['id'])) {
            $_SESSION['mensagem'] = 'ID do plano não fornecido';
            header('Location: index.php?page=relatorios');
            exit;
        }
        
        $id = (int)$_GET['id'];
        $plano = $this->planoEnsinoModel->getById($id);
        
        if (!$plano || $plano['status'] !== 'aprovado') {
            $_SESSION['mensagem'] = 'Plano não encontrado ou não está aprovado';
            header('Location: index.php?page=relatorios');
            exit;
        }
        
        // Iniciar PDF com DOMPDF
        $dompdf = new Dompdf();
        $html = $this->generateHtmlForPdf($plano);
        
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();
        
        $filename = 'Plano_Ensino_' . $plano['disciplina_codigo'] . '_' . $plano['semestre'] . '_' . $plano['ano'] . '.pdf';
        
        // Saída do PDF para download
        $dompdf->stream($filename, array('Attachment' => true));
        exit;
    }
    
    // Método auxiliar para gerar HTML para PDF
    private function generateHtmlForPdf($plano) {
        ob_start();
        include('views/relatorios/plano_pdf_template.php');
        return ob_get_clean();
    }
    
    // Método auxiliar para adicionar linhas em tabelas Word
    private function addTableRow(&$table, $label, $value) {
        $row = $table->addRow();
        $row->addCell(2000)->addText(htmlspecialchars($label), ['bold' => true]);
        $row->addCell(6000)->addText(htmlspecialchars($value));
    }
    
    // Método auxiliar para carregar views
    private function renderView($view, $data = []) {
        extract($data);
        
        require_once 'views/templates/header.php';
        require_once 'views/' . $view . '.php';
        require_once 'views/templates/footer.php';
    }
}
?>
