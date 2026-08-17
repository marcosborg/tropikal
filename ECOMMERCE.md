# Operação da loja online Tropikal

## Ativação

1. Criar uma conta Stripe e concluir a verificação da empresa.
2. Começar em modo de teste e configurar `STRIPE_KEY`, `STRIPE_SECRET` e `STRIPE_WEBHOOK_SECRET` no `.env`.
3. No Stripe, criar o webhook `https://tropikal.pt/stripe/webhook` para `checkout.session.completed`, `checkout.session.async_payment_succeeded`, `checkout.session.async_payment_failed` e `checkout.session.expired`.
4. No Filament, atribuir preço final com IVA, stock e “Compra online ativa” apenas aos produtos prontos a vender.
5. Rever portes (atualmente 9,90 €, gratuitos desde 250 €), países de entrega, IVA, política de devoluções e textos legais antes da ativação pública.

Sem `STRIPE_SECRET`, o checkout fica visivelmente indisponível e não cria encomendas nem tenta cobrar.

## Fluxo operacional

- A conta de cliente inclui dados, moradas, encomendas e devoluções.
- A encomenda nasce como `pending_payment`; só o webhook assinado do Stripe a coloca como paga e reduz stock.
- O processamento é idempotente: o mesmo evento ou regresso do navegador não duplica stock, descontos ou emails.
- O cliente e a Tropikal recebem a confirmação após pagamento.
- Produtos sem preço ou sem “Compra online ativa” permanecem “Sob consulta”.
- Códigos de desconto suportam percentagem ou valor fixo, mínimo, teto, datas e limites globais/por cliente.
- As devoluções são pedidas pelo cliente. O reembolso é acionado no Filament e enviado diretamente ao Stripe, nunca por edição manual do estado.

## Antes de produção

- Confirmar identidade legal, morada, NIF, contactos, faturação e política de privacidade/cookies.
- Definir zonas, custos, transportadoras, prazos e restrições para Açores, Madeira e continente.
- Confirmar se os preços incluem IVA e quais produtos têm taxas diferentes.
- Configurar SMTP real, filas/cron e cópias de segurança da base de dados.
- Realizar compras, falhas, pagamentos assíncronos, cupões, reembolsos totais/parciais e reposição de stock em modo de teste Stripe.
- Só depois trocar para chaves live e repetir uma compra real de baixo valor.
