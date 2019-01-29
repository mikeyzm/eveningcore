<script>
    export default {
        data() {
            return {
                filename: this.placeholder,
                options: {
                    tempo: 1.3,
                    pitch: 1.3,
                    volume: 1.0,
                    sampleRate: false,
                }
            }
        },
        props: [
            'placeholder'
        ],
        mounted() {

        },
        methods: {
            _getSelectFiles(input) {
                if (input.hasAttribute('multiple') && !!window.File) {
                    return [].slice.call(input.files)
                        .map((file) => file.name)
                        .join(', ')
                }
                if (input.value.indexOf('fakepath') !== -1) {
                    const splittedValue = input.value.split('\\');
                    return splittedValue[splittedValue.length - 1]
                }
                return input.value
            },
            onFileChange(e) {
                let fileName = this._getSelectFiles(e.target);
                this.filename = fileName ? fileName : this.placeholder;
            }
        }
    }
</script>
