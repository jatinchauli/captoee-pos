<template>
	<div class="main-layout-wrapper">
		<nav aria-label="breadcrumb">
            <ol class="breadcrumb bg-transparent m-0">
                <li class="breadcrumb-item">
                    <!-- <span>Here: {{trans('lang.custom_text')}} {{trans('custom.custom_text')}} {{trans('application.add_sales')}}</span>
                    <span>Here2: {{trans('lang.add_sales')}} {{trans('lang.custom_text')}} {{trans('lang.add_sales')}}</span> -->
                    <span>{{ trans('lang.add_sales') }}</span>
                </li>
            </ol>
        </nav>
        <div class="container-fluid p-0 add-sale-form">
            <div class="row mr-0">
                 <div class="col-12 col-md-12 col-lg-12 col-xl-12 pr-0">
                    <div class="main-layout-card">
                        <div class="main-layout-card-content">
                            <div class="row">
                                <div class="col-md-6">
                                    <!-- <form> -->
                                        <div class="form-row">
                                            <div class="form-group col-md-6">
                                                <label for="market_place">{{ trans('lang.market_place') }}</label>
                                                <select v-model="item.market_place" id="market_place" class="custom-select">
                                                    <option value="" disabled>{{ trans('lang.choose_one') }}</option>
                                                    <option value="Amazon"> {{ trans('lang.amazon') }}</option>
                                                    <option value="Flipkart"> {{ trans('lang.flipkart') }}</option>
                                                </select>
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label for="awb_code">{{ trans('lang.awb_code') }}</label>
                                                <input type="text" class="form-control" id="awb_code" v-model="item.awb_code"/>
                                            </div>
                                            <div class="form-group col-md-12">
                                                <label for="product_code">{{ trans('lang.product_code') }}</label>
                                                <input type="text" class="form-control" id="product_code" v-model="product_code"/>
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label for="sale_brand">{{ trans('lang.sale_brand') }}</label>
                                                <input type="text" class="form-control" id="sale_brand" v-model="productData.brand_name" disabled/>
                                            </div>
                                            <div class="form-group col-md-6" v-for="attribute in productData.attributes" :key="attribute.id">
                                                <label :for="attribute.name">{{ attribute.name }}</label>
                                                <input type="text" class="form-control" :id="attribute.name" :value="attribute.value" disabled/>
                                            </div>
                                            <!-- <div class="form-group col-md-6">
                                                <label for="color">{{ trans('lang.color') }}</label>
                                                <div class="d-flex color-section">
                                                    <div class="color-box color-red" data-id="red" @click="handleColor($event)"></div>
                                                    <div class="color-box color-black" data-id="black" @click="handleColor($event)"></div>
                                                    <div class="color-box color-white" data-id="white" @click="handleColor($event)"></div>
                                                </div>
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label for="color">{{ trans('lang.sale_size') }}</label>
                                                <div class="d-flex size-section">
                                                    <button type="button" :class="{ 'btn btn-primary btn-size' : true, 'active-size' : item.size == 'S' }" @click="handleSize('S')">S</button>
                                                    <button type="button" :class="{ 'btn btn-primary btn-size' : true, 'active-size' : item.size == 'M' }" @click="handleSize('M')">M</button>
                                                    <button type="button" :class="{ 'btn btn-primary btn-size' : true, 'active-size' : item.size == 'L' }" @click="handleSize('L')">L</button>
                                                </div>
                                            </div> -->
                                            
                                            <!-- <div class="form-group col-md-6">
                                                <label for="seller">{{ trans('lang.seller') }}</label>
                                                <input type="text" class="form-control" id="seller" v-model="item.seller"/>
                                            </div> -->
                                            <div class="form-group col-md-6">
                                                <label for="qty">{{ trans('lang.seller') }}</label>
                                                <select v-model="sellerId" id="productData-seller" class="custom-select" :disabled="isSellerDisabled == true">
                                                    <option value disabled selected>{{ trans('lang.choose_one') }}</option>
                                                    <option v-for="seller in sellerList" :key="seller.id" :value="seller.id">
                                                        {{ seller.first_name +' '+ seller.last_name }}
                                                    </option>
                                                </select>
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label for="qty">{{ trans('lang.qty') }}</label>
                                                <input type="number" class="form-control" id="qty" v-model="item.qty" :disabled="disabled == true" @input="handleQty"/> <!--@change="handleQty"-->
                                                <div v-if="isOutOfStock == true">
                                                    <p class="text-danger mt-2">{{ alertMessage }}</p>
                                                </div>
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label for="mrp">{{ trans('lang.mrp') }}</label>
                                                <input type="number" class="form-control" id="mrp" v-model="item.mrp" :disabled="disabled == true"/>
                                            </div>
                                        </div>
                                        <div class="form-row">
                                            <div class="form-group col-md-6">
                                                <button class="btn pay-btn app-color" @click="addSale()">
                                                    {{ trans('lang.sale_add') }}
                                                </button>
                                            </div>
                                        </div>
                                    <!-- </form> -->
                                </div>
                                <div class="col-md-2">
                                    <img v-if="productData.product_img" class="img-thumbnail" :src="publicPath+'/uploads/products/' + productData.product_img">
                                    <h5 class="mt-2">{{ productData.product_name }}</h5>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
	</div>
</template>


<script>
export default {
    data() {
        return {
            item : {
                market_place : '',
                awb_code : '',
                brand : '',
                brand_id : '',
                seller : '',
                qty : '',
                mrp : ''
            },
            product_code : '',
            productData : [],
            sellerList : [],
            quantity : '',
            disabled : true,
            alertMessage : '',
            isOutOfStock : false,
            sellerId : '',
            isSellerDisabled : true,
            total_stocks : '',
            timeout : null
        }
    },

    mounted() {
       
    },

    methods : {

        fetchProductDetails() {
            let instance = this;
            axios.get('getProductDetails', {
                params : {
                    'sku' : this.product_code,
                }
            })
            .then(res => {
                var data = res.data;
                this.productData = data;
                if(this.productData)
                    this.isSellerDisabled = false;
                
            });
        },

        handleQty() {
            if(this.item.qty < 0) {
                this.item.qty = 0;
            } else if(this.item.qty > 0 && this.item.qty <= this.total_stocks) {
                this.disabled = false;
                this.alertMessage = (this.total_stocks - this.item.qty) + this.handleAlertMessage(this.total_stocks - this.item.qty);
            } else if(this.item.qty > this.total_stocks) {
                this.item.qty = this.total_stocks;
            } else if(this.item.qty == 0) {
                this.alertMessage = (this.total_stocks - this.item.qty) + this.handleAlertMessage(this.total_stocks - this.item.qty);
            }
        },

        handleAlertMessage(qty) {
            if(qty == 1) {
                return  ' ' + this.trans('lang.item_left_in_stock')
            } else {
                return  ' ' + this.trans('lang.items_left_in_stock')
            }
        },

        fetchSuppliers() {
            axios.get('getSellerList', {
                params : {
                    'sku' : this.product_code,
                }
            })
            .then(res => {
                this.sellerList = res.data
            })
        },

        addSale() {
            this.item.product_code = this.product_code;
            axios.post('save-sales',this.item)
            .then(res => {
                this.item = {
                    market_place : '',
                    awb_code : '',
                    product_code : '',
                    brand : '',
                    seller : '',
                    qty : '',
                    mrp : '',
                }; 
                this.product_code = null;
            });
        },

        handleFieldDisabled(data) {
            if(data) {
                this.disabled = false;
            } else {
                this.disabled = true;
            }
        },

        fetchStockOnSeller() {
            console.log(this.sellerId);
            let item = {
                'sku' : this.product_code,
                'sellerId' : this.sellerId 
            }
            axios.get('fetchStock',{
                params : item
            })
            .then(res => {
                
                this.total_stocks = res.data.stock;
                this.isOutOfStock = true;
                if(this.total_stocks == 0) {
                    this.disabled = true;
                    this.alertMessage = data.product_name + this.trans('lang.is_out_of_stock');
                } else {
                    if(this.total_stocks) {
                        this.alertMessage = this.total_stocks + this.handleAlertMessage(this.total_stocks);
                        this.isOutOfStock = true;
                        this.disabled = false;
                    }
                }
            })
        }
    },

    watch : {
        product_code(newVal, oldVal) {
            this.isOutOfStock = false;
            this.alertMessage = null;
            this.isSellerDisabled = true;
            this.disabled = true;
            this.sellerId = null;
            this.productData.brand = null;
            this.productData.attributes = null;
            clearTimeout(this.timeout);
            this.timeout = setTimeout(() => {
                this.fetchProductDetails();
                this.fetchSuppliers();
            },500);
        },

        sellerId(newVal, oldVal) {
            this.fetchStockOnSeller();
        }
    }
}
</script>


<style scoped>
    .add-sale-form .color-section,
    .add-sale-form .size-section {
        gap: 5px;
    }
    .add-sale-form .color-box {
        width: 25px;
        height: 25px;
        border: 1px solid;
        position: relative;
    }

    .add-sale-form .color-box:hover {
        border: none;
    }

    .add-sale-form .color-box:hover::before,
    .add-sale-form .color-box.active-color::before {
        content: '';
        border: 2px solid #4d8dff;
        position: absolute;
        top: -1px;
        right: -1px;
        bottom: -1px;
        left: -1px;
    }

    .add-sale-form .color-box.color-red {
        background: #e02727;
    }
    
    .add-sale-form .color-box.color-white {
        background: #fff;
    }
    
    .add-sale-form .color-box.color-black {
        background: #000;
        border: 1px solid #fff;
    }

    .add-sale-form .size-section .btn-size {
        width: 40px;
        height: 40px;
        padding: 0px!important;
        display: flex;
        justify-content: center;
        align-items: center;
        border-radius: 50%;
        font-size: 16px!important;
        background: #fff;
        border: 1px solid #6d6180;
        color: #281a3e;
    }
    
    .add-sale-form .size-section .btn-size:hover,
    .add-sale-form .size-section .btn-size.active-size {
        border: 2px solid #4d8dff;
    }
</style>