<?php require BASE_PATH . '/app/views/layouts/header.php'; ?>

<div class="container">
    <div class="checklist-section" style="max-width: 800px; margin-top: 60px; margin-bottom: 80px; padding: 40px;">
        <h1 style="color: var(--primary); text-align: center; margin-bottom: 20px;">Contato</h1>

        <p style="text-align: center; color: var(--text-muted); font-size: 1.1rem; margin-bottom: 40px;">
            Dúvidas, sugestões ou feedbacks sobre o projeto? <br>
            Sinta-se à vontade para entrar em contato diretamente conosco.
        </p>

        <div style="display: flex; gap: 20px; flex-wrap: wrap; justify-content: center; margin-bottom: 30px;">

            <div style="background-color: var(--white); border: 1px solid #e2e8f0; padding: 25px; border-radius: 12px; flex: 1; min-width: 280px; text-align: center; box-shadow: 0 4px 6px rgba(0,0,0,0.05);">
                <h3 style="color: var(--primary); margin-bottom: 15px;">Leonardo Henrique</h3>
                <p style="font-size: 0.95rem; word-break: break-all;">
                    <a href="mailto:20241pin10030021@estudantes.ifpr.edu.br"
                       style="color: var(--primary); text-decoration: none; font-weight: bold;">
                        20241pin10030021@estudantes.ifpr.edu.br
                    </a>
                </p>
            </div>

            <div style="background-color: var(--white); border: 1px solid #e2e8f0; padding: 25px; border-radius: 12px; flex: 1; min-width: 280px; text-align: center; box-shadow: 0 4px 6px rgba(0,0,0,0.05);">
                <h3 style="color: var(--primary); margin-bottom: 15px;">Maira Michelon</h3>
                <p style="font-size: 0.95rem; word-break: break-all;">
                    <a href="mailto:mairamichelon@gmail.com"
                       style="color: var(--primary); text-decoration: none; font-weight: bold;">
                        mairamichelon@gmail.com
                    </a>
                </p>
            </div>

        </div>

        <div style="background-color: #fffaf0; border: 1px dashed #ed8936; padding: 20px; border-radius: 8px; text-align: center; margin-top: 20px;">
            <p style="color: #c05621; margin: 0; font-weight: 500;">
                <strong>💡 Observação:</strong> Para garantir uma resposta mais rápida, pedimos que direcione sua mensagem para <strong>ambos os e-mails</strong> acima.
            </p>
        </div>

        <div style="text-align: center; margin-top: 50px;">
            <a href="/" class="btn-secondary" style="text-decoration: none;">← Voltar para o Início</a>
        </div>
    </div>
</div>

<?php require BASE_PATH . '/app/views/layouts/footer.php'; ?>
