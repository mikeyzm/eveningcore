<script>
    export default {
        data() {
            return {
                convert: this._convert,
                channel: 'convert.' + this._convert.id,
                removing: false,
                removed: false,
            }
        },
        props: [
            '_convert'
        ],
        computed: {
            statusClass() {
                switch (this.convert.status) {
                    case 0:
                        return 'badge-secondary';
                    case 1:
                        return 'badge-warning';
                    case 2:
                        return 'badge-success';
                    case 3:
                        return 'badge-danger';
                    default:
                        return 'badge-light';
                }
            }
        },
        watch: {
            convert(n) {
                if (![0, 1].includes(n.status)) {
                    window.Echo.leave(this.channel);
                }
            },
        },
        mounted() {
            if ([0, 1].includes(this.convert.status)) {
                window.Echo.channel(this.channel)
                    .listen('ConvertStatusUpdated', (data) => {
                        this.convert = data.convert;
                    });
            }
        }
    }
</script>
