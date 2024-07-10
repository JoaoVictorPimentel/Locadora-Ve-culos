<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <!-- Card de busca -->
                <card-component titulo="Busca de marcas">
                    <!--Conteudo -->
                    <template v-slot:conteudo>
                        <div class="row g-3">
                            <div class="col mb-3">
                                <input-container-component titulo="ID" id="inputId" id-help="idHelp"
                                    texto-ajuda="Opcional. Informe o ID da marca!">
                                    <input type="number" class="form-control" id="inputId" aria-describedby="idHelp"
                                        placeholder="ID">
                                </input-container-component>
                            </div>
                            <div class="col mb-3">
                                <input-container-component titulo="Nome da marca" id="inputNome" id-help="nomeHelp"
                                    texto-ajuda="Opcional. Informe o I=nome da marca!">
                                    <input type="text" class="form-control" id="inputNome" aria-describedby="nomeHelp"
                                        placeholder="Nome da marca">
                                </input-container-component>
                            </div>
                        </div>
                    </template>
                    <!--Rodape -->
                    <template v-slot:rodape>
                        <button type="submit" class="btn btn-primary btn-sm float-right">Pesquisar</button>
                    </template>
                </card-component>
                <!-- Card listagem -->
                <card-component titulo="Relação de marcas">
                    <!-- Conteudo -->
                    <template v-slot:conteudo>
                        <table-component></table-component>
                    </template>
                    <!-- Rodape -->
                    <template v-slot:rodape>
                        <button type="button" class="btn btn-primary btn-sm float-right" data-bs-toggle="modal"
                            data-bs-target="#modalMarca">Adicionar</button>
                    </template>
                </card-component>
            </div>
        </div>

        <modal-component id="modalMarca" titulo="Adicionar marca">
            <template v-slot:conteudo>
                <div class="form-group mb-3">
                    <input-container-component titulo="Nome da marca" id="novoNome" id-help="novoNomeHelp" texto-ajuda="Informe o ID da marca!">
                        <input type="text" 
                            class="form-control" 
                            id="novoNome" 
                            aria-describedby="novoNomeHelp" 
                            placeholder="Nome da marca"
                            v-model="nomeMarca">
                    </input-container-component>
                </div>
                <div class="form-group mb-3">
                    <input-container-component titulo="Imagem" id="novoImagem" id-help="novoImagemHelp" texto-ajuda="Adicione uma imagem PNG!">
                        <input type="file" 
                            class="form-control" 
                            id="novoImagem" 
                            aria-describedby="novoImagemHelp" 
                            placeholder="Adicione uma imagem"
                            @change="carregarImagem($event)">
                    </input-container-component>
                </div>
            </template>
            <template v-slot:rodape>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                <button type="button" class="btn btn-primary" @click="salvar">Salvar</button>
            </template>
        </modal-component>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                urlBase: 'http://localhost:8000/api/v1/marca',
                nomeMarca: '',
                arquivoImagem: []
            }
        },
        methods: {
            carregarImagem(e) {
                this.arquivoImagem = e.target.files
            },
            salvar() {
                console.log(this.nomeMarca, this.arquivoImagem[0])

                let formData = new FormData();
                formData.append('nome', this.nomeMarca)
                formData.append('imagem', this.arquivoImagem[0])

                let config = {
                    headers: {
                        'Content-Type': 'multipart/form-data',
                        'Accept': 'application/json'
                    }
                }

                axios.post(this.urlBase, formData, config)
                    .then(response => {
                        console.log(response)
                    })
                    .catch(errors => {
                        console.log(errors)
                    })
            }
        }
    }
</script>