export function formatDate(date?: string | null): string {
    if (!date) return ''

    const d = new Date(date)

    return d.toLocaleDateString('pt-BR', {
        day: '2-digit',
        month: '2-digit',
        year: 'numeric'
    })
}

export function formatDateShort(date?: string | null): string {
    if (!date) return ''

    const d = new Date(date)

    return d.toLocaleDateString('pt-BR', {
        day: '2-digit',
        month: 'short',
        year: 'numeric'
    })
}

export function formatDateTime(date?: string | null): string {
    if (!date) return ''

    const d = new Date(date)

    return d.toLocaleString('pt-BR', {
        day: '2-digit',
        month: '2-digit',
        year: 'numeric',
        hour: '2-digit',
        minute: '2-digit'
    })
}

export function formatRelative(date?: string | null): string {
    if (!date) return ''

    const now = new Date()
    const d = new Date(date)

    const diff = Math.floor((now.getTime() - d.getTime()) / 1000)

    if (diff < 60) return 'agora'
    if (diff < 3600) return `${Math.floor(diff / 60)} min atrás`
    if (diff < 86400) return `${Math.floor(diff / 3600)} h atrás`

    return `${Math.floor(diff / 86400)} dias atrás`
}
