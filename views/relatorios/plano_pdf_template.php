<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Plano de Ensino</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12pt;
            line-height: 1.5;
            margin: 2cm;
        }
        h1 {
            font-size: 16pt;
            text-align: center;
            margin-bottom: 20px;
        }
        h2 {
            font-size: 14pt;
            border-bottom: 1px solid #ddd;
            padding-bottom: 5px;
            margin-top: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }
        table, th, td {
            border: 1px solid #ddd;
        }
        th, td {
            padding: 8px;
            text-align: left;
        }
        th {
            font-weight: bold;
            width: 30%;
        }
        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 10pt;
            color: #666;
        }
    </style>
</head>
<body>
    <h1>PLANO DE ENSINO</h1>
    
    <h2>1. Identificação</h2>
    <table>
        <tr>
            <th>Disciplina:</th>
            <td><?php echo htmlspecialchars($plano['disciplina_nome'] . ' - ' . $plano['disciplina_codigo']); ?></td>
        </tr>
        <tr>
            <th>Curso:</th>
            <td><?php echo htmlspecialchars($plano['curso_nome']); ?></td>
        </tr>
        <tr>
            <th>Professor:</th>
            <td><?php echo htmlspecialchars($plano['professor_nome']); ?></td>
        </tr>
        <tr>
            <th>Semestre:</th>
            <td><?php echo $plano['semestre']; ?>/<?php echo $plano['ano']; ?></td>
        </tr>
    </table>
    
    <h2>2. Objetivos</h2>
    <h3>2.1 Objetivo Geral:</h3>
    <p><?php echo nl2br(htmlspecialchars($plano['objetivos_gerais'])); ?></p>
    
    <h3>2.2 Objetivos Específicos:</h3>
    <p><?php echo nl2br(htmlspecialchars($plano['objetivos_especificos'])); ?></p>
    
    <h2>3. Metodologia</h2>
    <p><?php echo nl2br(htmlspecialchars($plano['metodologia'])); ?></p>
    
    <h2>4. Recursos Didáticos</h2>
    <p><?php echo nl2br(htmlspecialchars($plano['recursos_didaticos'])); ?></p>
    
    <h2>5. Avaliação</h2>
    <p><?php echo nl2br(htmlspecialchars($plano['avaliacao'])); ?></p>
    
    <h2>6. Bibliografia</h2>
    <h3>6.1 Bibliografia Básica:</h3>
    <p><?php echo nl2br(htmlspecialchars($plano['bibliografia_basica'])); ?></p>
    
    <h3>6.2 Bibliografia Complementar:</h3>
    <p><?php echo nl2br(htmlspecialchars($plano['bibliografia_complementar'])); ?></p>
    
    <h2>7. Cronograma</h2>
    <p><?php echo nl2br(htmlspecialchars($plano['cronograma_detalhado'])); ?></p>
    
    <?php if(!empty($plano['observacoes'])): ?>
    <h2>8. Observações</h2>
    <p><?php echo nl2br(htmlspecialchars($plano['observacoes'])); ?></p>
    <?php endif; ?>
    
    <div class="footer">
        <p>Plano aprovado em: <?php echo date('d/m/Y', strtotime($plano['data_aprovacao'])); ?></p>
        <p>GECOPEC - Sistema de Gestão de Cronogramas e Planos de Ensino</p>
    </div>
</body>
</html>
