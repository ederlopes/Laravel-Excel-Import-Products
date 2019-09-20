<template>
    <div>
        <top-nav-bar class="mb-4"></top-nav-bar>
        <b-container>
            <b-card header="Cadastro de produtos" class="mb-4">
                <b-form-file type="file" ref="fileinput" accept=".xls, .xlsx" v-model="file" placeholder="Escolha seu arquivo excel..." @input="storeProdutct"></b-form-file>
            </b-card>
            <b-card header="Produtos cadastrados">

                <!-- Input para pesquisar os registros cadastrados no bando de dados -->
                <b-row>
                    <b-col md="12" class="my-3">
                        <b-form-group label="Pesquisar" class="mb-2">
                            <b-input-group>
                                <b-form-input v-model="filter" placeholder="Digite para pesquisar" />
                                <b-input-group-append>
                                    <b-btn :disabled="!filter" @click="filter = ''">Limpar</b-btn>
                                </b-input-group-append>
                            </b-input-group>
                        </b-form-group>
                    </b-col>
                </b-row>

                <!-- Tabela de exibição dos produtos -->
                <b-table :items="products" :fields="fields" :filter="filter" :current-page="currentPage" :per-page="perPage">
                    <template slot="actions" slot-scope="row">
                        <!-- We use @click.stop here to prevent a 'row-clicked' event from also happening -->
                        <b-button size="sm" @click.stop="editProduct(row.item, row.index, $event.target)" class="mr-1">
                            Editar
                        </b-button>
                        <b-button size="sm" @click.stop="row.toggleDetails">
                            {{ row.detailsShowing ? 'Esconder' : 'Visualizar' }} detalhes
                        </b-button>
                        <b-button type="dark" variant="danger" size="sm" @click.stop="deleteProduct(row.item, row.index, $event.target)" class="mr-1">
                            Apagar
                        </b-button>
                    </template>
                    <template slot="row-details" slot-scope="row">
                        <b-card>
                            <ul>
                                <li v-for="(value, key) in row.item" :key="key">{{ key }}: {{ value}}</li>
                            </ul>
                        </b-card>
                    </template>
                </b-table>

                <!-- Paginação da tabela -->
                <b-row>
                    <b-col md="6" class="my-1">
                        <b-pagination :total-rows="totalRows" :per-page="perPage" v-model="currentPage" class="my-0" />
                    </b-col>
                </b-row>

            </b-card>

            <!-- Modal para a edição de produtos -->
            <b-modal id="editModal" :title="editModal.name" @ok="updateProduct">
                <form @submit.stop.prevent="updateProduct">
                    <b-form-input type="number" v-model="productEdit.im" class="mb-2">
                    </b-form-input>
                    <b-form-input type="text" v-model="productEdit.name" class="mb-2">
                    </b-form-input>
                    <b-form-checkbox v-model="productEdit.free_shipping" value="1" unchecked-value="0" class="mb-2">
                        Free Shipping
                    </b-form-checkbox>
                    <b-form-textarea v-model="productEdit.description" :rows="3" class="mb-2">
                    </b-form-textarea>
                    <b-form-input type="text" v-model="productEdit.price" class="mb-2">
                    </b-form-input>
                </form>
            </b-modal>
        </b-container>
    </div>
</template>

<script>
import TopNavBar from '../components/TopNavBar'

export default {
    data() {
        return {
            file: null,
            fields: [
                { key: 'im', sortable: true },
                { key: 'name', sortable: true },
                { key: 'price', sortable: true },
                { key: 'created_at', sortable: true },
                { key: 'actions', label: 'Actions' }
            ],
            products: [],
            filter: null,
            currentPage: 1,
            perPage: 5,
            editModal: {
                name: ''
            },
            productEdit: {}
        }
    },
    components: {
        TopNavBar
    },
    created() {
        // Quando a pagina for criada, chamar a função para pegar os produtos no banco de dados
        this.getProducts()
    },
    methods: {
        getProducts() {
            /**
             * Função para pegar os produtos no banco de dados
             */
            axios.get('products').then(result => {
                this.products = result.data
            });
        },
        editProduct(item) {
            /**
             * Função responsavel por pegar o item que será editado e chamar o modal para a edição do produto
             */
            this.editModal.name = 'Editando: ' + item.name
            this.productEdit = item
            this.$root.$emit('bv::show::modal', 'editModal')
        },
        deleteProduct(item) {
            /**
             * Função responsavel por deletar o item selecionado
             */
            // Notificação de alerta validando se o usuário realmente deseja excluir o item
            this.$swal({
                title: 'Atenção',
                text: "Você tem certeza que deseja excluir este produto?",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Sim'
            }).then((result) => {
                //Validando a resposta, caso ele clique em sim entra no if abaixo
                if (result.value) {

                    // Enviando para a API deletar o registro do banco
                    axios.delete('products/' + item.id)
                        .then(result => {

                            //Notificando o usuário que o produto foi deletado com sucesso
                            this.$swal('Deletado!', result.data, 'success')

                            //Procurando o produto deletado, para remover da lista de produtos
                            let index = this.products.findIndex(product => product.id === item.id);
                            this.products.splice(index, 1)


                        }).catch(error => {

                            // Caso de erro em deletar o produtos alertamos o usuário
                            // Console log para debug
                            console.log(error)
                            this.$swal('Ops!', 'Não foi pósivel excluir o produto', 'warning')

                        })

                }
            })
        },
        storeProdutct() {
            /**
             * Função para o cadastro de produtos atraves de planilha excel
             */

            // Primeiro verificamos se o input file não está vazio, apenas um prevent de envio
            if (this.file !== null) {

                let formData = new FormData();
                formData.append('file', this.file);

                axios.post('products',
                    formData, {
                        headers: {
                            'Content-Type': 'multipart/form-data'
                        }
                    }).then(result => {

                        // Resetar o campo input para o padrão
                        this.$refs.fileinput.reset();

                        // Resetar os produtos e pegar os novos que foram cadastrados
                        this.products = []
                        this.getProducts()

                        // Alertar o usuário que o produto foi cadastrado com sucesso
                        this.$swal('Boa!', result.data, 'success')

                    }).catch(error => {

                        // Caso de erro no cadastro alertamos o usuário o motivo
                        // Console log para debug
                        console.log(error)
                        this.$swal('Ops!', 'O Arquivo escolhido não possui os campos de acordo', 'warning')

                        // Resetamos o campo de input para o padrão
                        this.$refs.fileinput.reset();

                    });
            }
        },
        updateProduct() {
            /**
             * Função para atualizar os dados do produto
             */
            axios.put('products/' + this.productEdit.id, this.productEdit)
                .then(result => {
                    //
                    this.$swal('Boa!', result.data, 'success')

                }).catch(error => {

                    // Caso de erro no cadastro alertamos o usuário o motivo
                    // Console log para debug
                    console.log(error)
                    this.$swal('Ops!', 'O Arquivo escolhido não possui os campos de acordo', 'warning')

                    // Resetamos o campo de input para o padrão
                    this.$refs.fileinput.reset();

                });


        }
    },
    computed: {
        // Propriedade responsavel por retornar a quantidade de registro na tabela
        totalRows() {
            if (this.products.length) {
                return this.products.length
            } else {
                return 0
            }
        }
    }
}
</script>
