import ReactMarkdown from 'react-markdown'
import remarkGfm from 'remark-gfm'
import CodigoEjemplo from './CodigoEjemplo'

export default function TeoriaBloque({ leccion }) {
  return (
    <div className="bg-[#161b22] border border-[#30363d] rounded-xl p-5 mb-4">
      {leccion.tipo === 'tip' && (
        <div className="flex items-center gap-2 mb-3 text-[#a3e635] text-sm font-semibold">
          <span>💡</span> Tip
        </div>
      )}
      {leccion.tipo === 'video' && leccion.contenido && (
        <div className="aspect-video rounded-lg overflow-hidden mb-4 bg-[#0d1117]">
          <iframe src={leccion.contenido} className="w-full h-full" allowFullScreen />
        </div>
      )}

      <div className="md">
        <ReactMarkdown
          remarkPlugins={[remarkGfm]}
          components={{
            code({ node, inline, className, children, ...props }) {
              const match = /language-(\w+)/.exec(className || '')
              const lang = match?.[1] ?? 'python'
              const code = String(children).replace(/\n$/, '')

              if (!inline && (match || code.includes('\n'))) {
                const lines = code.split('\n').length
                const altura = Math.min(Math.max(lines * 22 + 40, 80), 400)
                return <CodigoEjemplo codigo={code} lenguaje={lang} altura={altura} />
              }
              return <code className={className} {...props}>{children}</code>
            },
          }}
        >
          {leccion.contenido}
        </ReactMarkdown>
      </div>
    </div>
  )
}
