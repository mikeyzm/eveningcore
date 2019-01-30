<script>
    import dayjs from 'dayjs';

    export default {
        data() {
            return {
                convert: this._convert,
                channel: 'convert.' + this._convert.id,
                removing: false,
                removed: false,
                expiredAt: this._convert.expired_at,
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
            convert(newConvertObj) {
                if (![0, 1].includes(newConvertObj.status)) {
                    window.Echo.leave(this.channel);
                }
                if (newConvertObj.expired_at) {
                    this.expiredAt = dayjs(newConvertObj.expired_at).fromNow();
                }
            },
        },
        mounted() {
            if (this.expiredAt) {
                this.expiredAt = dayjs(this.expiredAt).fromNow();
            }
            if ([0, 1].includes(this.convert.status)) {
                window.Echo.channel(this.channel)
                    .listen('ConvertStatusUpdated', (data) => {
                        this.convert = data.convert;
                    });
            }
        }
    }
</script>
