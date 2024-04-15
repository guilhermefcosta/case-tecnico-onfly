<h1>Sua despesa foi criada com sucesso! </h1>

<p>Alguns detalhes sobre a despesa</p>

<p><b>Nome usuario:</b> {{ $user->name }} </p>
<p><b>Número do cartão</b> {{ $card->card_number }}</p>
<p><b>Valor despesa:</b> {{ number_format($expense->value, 2, ',', '.') }}</p>

<p>Muito obrigado!</p>