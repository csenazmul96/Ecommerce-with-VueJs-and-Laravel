<template>
    <div class="help-block with-errors">
        <ul class="list-unstyled">
            <li v-for="(err, index) in errors" :key="key(index)">{{ err }}</li>
        </ul>
    </div>
</template>

<script>
export default {
    props: ['errors'],
    methods: {
        key(index) {
            return `validation_error_${index}_${Math.random()}`
        }
    }
}
</script>

