function convertePonto(valor) {
    let valorOriginal = valor;

    if (typeof valor !== 'string') {
        valor = valor.toString();
    }

    if (valor.indexOf('.') === -1 && valor.indexOf(',') === -1) {
        return parseFloat(valor).toFixed(2).replace('.', ',');
    }

    // Verifica se o número tem um dígito após o ponto decimal
    if (valor.indexOf('.') !== -1) {
        let partes = valor.split('.');
        if (partes[1].length === 1) {
            // Adiciona um zero para representar os centavos
            partes[1] += '0';
            return partes.join(',');
        } else if (partes[1].length === 2) {
            return valor.replace('.', ',');
        }
    }

    // Verifica se o número tem vírgula em vez de ponto e corrige
    if (valor.indexOf(',') !== -1) {
        return valor.replace(',', '.');
    }

    // Se nenhum ajuste for necessário, retorna o valor original
    return valor;
}
