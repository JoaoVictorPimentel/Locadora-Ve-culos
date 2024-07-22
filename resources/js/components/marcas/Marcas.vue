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
                                        placeholder="ID" v-model="busca.id">
                                </input-container-component>
                            </div>
                            <div class="col mb-3">
                                <input-container-component titulo="Nome da marca" id="inputNome" id-help="nomeHelp"
                                    texto-ajuda="Opcional. Informe o nome da marca!">
                                    <input type="text" class="form-control" id="inputNome" aria-describedby="nomeHelp"
                                        placeholder="Nome da marca" v-model="busca.nome">
                                </input-container-component>
                            </div>
                        </div>
                    </template>
                    <!--Rodape -->
                    <template v-slot:rodape>
                        <button type="submit" @click="pesquisar"
                            class="btn btn-primary btn-sm float-right">Pesquisar</button>
                    </template>
                </card-component>
                <!-- Card listagem -->
                <card-component titulo="Relação de marcas">
                    <!-- Conteudo -->
                    <template v-slot:conteudo>
                        <table-component v-if="marcas.data" :dados="marcas.data"
                            :visualizar="{ visivel: true, dataToggle: 'modal', dataTarget: '#modalMarcaVisualizar' }"
                            :atualizar="true"
                            :remover="{ visivel: true, dataToggle: 'modal', dataTarget: '#modalMarcaRemover' }" :titulos="{
                                id: { titulo: 'ID', tipo: 'texto' },
                                nome: { titulo: 'Nome', tipo: 'texto' },
                                imagem: { titulo: 'Imagem', tipo: 'imagem' },
                                created_at: { titulo: 'Criação', tipo: 'data' }
                            }">
                        </table-component>
                    </template>
                    <!-- Rodape -->
                    <template v-slot:rodape>
                        <div class="row">
                            <div class="col-md">
                                <paginate-component>
                                    <li v-for="l, key in marcas.links" :key="key"
                                        :class="l.active ? 'page-item active' : 'page-item'" @click="paginacao(l)">
                                        <a class="page-link" v-html="l.label"></a>
                                    </li>
                                </paginate-component>
                            </div>
                            <div class="col-md">
                                <button type="button" class="btn btn-primary btn-sm float-right" data-bs-toggle="modal"
                                    data-bs-target="#modalMarca" @click.prevent="limparCampos">Adicionar</button>
                            </div>
                        </div>
                    </template>
                </card-component>
            </div>
        </div>

        <!-- Modal para adicionar nova marca -->
        <modal-component id="modalMarca" titulo="Adicionar marca">
            <template v-slot:alertas>
                <alert-component tipo="success" :detalhes="transicaoDetalhes" titulo="Cadastro realizado com sucesso!"
                    v-if="transicaoStatus == 'adicionado'"></alert-component>
                <alert-component tipo="danger" :detalhes="transicaoDetalhes" titulo="Erro ao tentar cadastrar"
                    v-if="transicaoStatus == 'erro'"></alert-component>
            </template>
            <template v-slot:conteudo>
                <div class="form-group mb-3">
                    <input-container-component titulo="Nome da marca" id="novoNome" id-help="novoNomeHelp"
                        texto-ajuda="Informe o ID da marca!">
                        <input type="text" class="form-control" id="novoNome" aria-describedby="novoNomeHelp"
                            placeholder="Nome da marca" v-model="nomeMarca">
                    </input-container-component>
                </div>
                <div class="form-group mb-3">
                    <input-container-component titulo="Imagem" id="novoImagem" id-help="novoImagemHelp"
                        texto-ajuda="Adicione uma imagem PNG!">
                        <input type="file" class="form-control" id="novoImagem" aria-describedby="novoImagemHelp"
                            placeholder="Adicione uma imagem" @change="carregarImagem($event)">
                    </input-container-component>
                </div>
            </template>
            <template v-slot:rodape>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                <button type="button" class="btn btn-primary" @click="salvar">Salvar</button>
            </template>
        </modal-component>

        <!-- Modal de visualizar marca -->
        <modal-component id="modalMarcaVisualizar" titulo="Visualizar Marca">
            <template v-slot:alertas>

            </template>

            <template v-slot:conteudo>
                <input-container-component titulo="ID">
                    <input type="text" class="form-control" :value="$store.state.item.id" disabled>
                </input-container-component>
                <input-container-component titulo="Nome">
                    <input type="text" class="form-control" :value="$store.state.item.nome" disabled>
                </input-container-component>
                <input-container-component titulo="Logo">
                    <img :src="'storage/' + $store.state.item.imagem" v-if="$store.state.item.imagem">
                </input-container-component>
                <input-container-component titulo="Data de criação">
                    <input type="text" class="form-control" :value="$store.state.item.created_at" disabled>
                </input-container-component>
            </template>

            <template v-slot:rodape>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
            </template>
        </modal-component>

        <!-- Modal de remover marca -->
        <modal-component id="modalMarcaRemover" titulo="Remover marca">
            <template v-slot:alertas>
                <alert-component tipo="success" titulo="Transação realizada com sucesso" :detalhes="$store.state.transacao" v-if="$store.state.transacao.status == 'sucesso'"></alert-component>
                <alert-component tipo="danger" titulo="Erro na transação" :detalhes="$store.state.transacao" v-if="$store.state.transacao.status == 'erro'"></alert-component>
            </template>
            <template v-slot:conteudo v-if="$store.state.transacao.status != 'sucesso'">
                <input-container-component titulo="ID">
                    <input type="text" class="form-control" :value="$store.state.item.id" disabled>
                </input-container-component>

                <input-container-component titulo="Nome da marca">
                    <input type="text" class="form-control" :value="$store.state.item.nome" disabled>
                </input-container-component>
            </template>
            <template v-slot:rodape>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                <button type="button" class="btn btn-danger" @click="remover()" v-if="$store.state.transacao.status != 'sucesso'">Remover</button>
            </template>
        </modal-component>
    </div>
</template>

<script>
export default {
    computed: {
        token() {
            let token = document.cookie.split(';').find(indice => {
                return indice.includes('token=')
            })

            token = token.split('=')[1]
            token = 'Bearer' + token

            return token
        },

        item() {
            return this.$store.state.item;
        },

        transacao() {
            return this.$store.state.transacao;
        }
    },
    data() {
        return {
            urlBase: 'http://localhost:8000/api/v1/marca',
            urlPaginacao: '',
            urlFiltro: '',
            nomeMarca: '',
            arquivoImagem: [],
            transicaoStatus: '',
            transicaoDetalhes: {},
            marcas: [],
            busca: { id: '', nome: '' }
        }
    },
    methods: {
        remover() {
            let confirmacao = confirm('Tem certeza que deseja remover esse registro?')

            if (!confirmacao) {
                return false;
            }

            let formData = new FormData();
            formData.append('_method', 'delete')

            let config = {
                headers: {
                    'Accept': 'application/json',
                    'Authorization': this.token
                }
            }

            let url = this.urlBase + '/' + this.$store.state.item.id
            
            axios.post(url, formData, config)
                .then(response => {
                    this.$store.state.transacao.status = 'sucesso'
                    this.$store.state.transacao.mensagem = response.data.msg

                    this.carregarLista()
                })
                .catch(errors => {
                    this.$store.state.transacao.status = 'erro'
                    this.$store.state.transacao.mensagem = errors.response.data.erro
                })
        },

        pesquisar() {
            let filtro = ''

            for (let chave in this.busca) {
                if (this.busca[chave]) {
                    if (filtro != '') {
                        filtro += ';'
                    }
                    filtro += chave + ':like:' + this.busca[chave]
                }
            }

            if (filtro != '') {
                this.urlPaginacao = 'page=1'
                this.urlFiltro = '&filtro=' + filtro
            } else {
                this.urlFiltro = ''
            }

            this.carregarLista()
        },

        paginacao(l) {
            if (l.url) {
                //this.urlBase = l.url
                this.urlPaginacao = l.url.split('?')[1]
                this.carregarLista()
            }
        },

        carregarLista() {
            let url = this.urlBase + '?' + this.urlPaginacao + this.urlFiltro

            let config = {
                headers: {
                    'Accept': 'application/json',
                    'Authorization': this.token
                }
            }

            axios.get(url, config)
                .then(response => {
                    this.marcas = response.data
                })
                .catch(errors => {
                    console.log(errors)
                })
        },

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
                    'Accept': 'application/json',
                    'Authorization': this.token
                }
            }

            axios.post(this.urlBase, formData, config)
                .then(response => {
                    this.transicaoStatus = 'adicionado'
                    this.transicaoDetalhes = {
                        mensagem: 'ID do registro:' + response.data.id
                    }
                    //console.log(response)
                })
                .catch(errors => {
                    this.transicaoStatus = 'erro'
                    this.transicaoDetalhes = {
                        mensagem: errors.response.data.message,
                        listarErros: errors.response.data.errors
                    }
                    //console.log(errors.response.data.message)
                })
        },

        limparCampos() {
            this.transicaoStatus = ''
            this.transicaoDetalhes = {}
            this.$store.state.transacao.status = ''
            this.$store.state.transacao.mensagem = ''
        },
    },

    mounted() {
        this.carregarLista()
    }
}
</script>