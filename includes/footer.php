    <footer style="background: #333; color: white; text-align: center; padding: 2rem; margin-top: 4rem;">
        <div class="container">
            <p>&copy; 2024 SkillSwap. Todos os direitos reservados.</p>
            <p>Troque habilidades. Aprenda de verdade.</p>
        </div>
    </footer>
    
    <script>
        // Adicionar efeitos de hover nos cards
        document.querySelectorAll('.card').forEach(card => {
            card.addEventListener('mouseenter', function() {
                this.style.transform = 'translateY(-5px)';
            });
            card.addEventListener('mouseleave', function() {
                this.style.transform = 'translateY(0)';
            });
        });
    </script>
</body>
</html>