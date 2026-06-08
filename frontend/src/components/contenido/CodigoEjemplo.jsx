import Editor from '@monaco-editor/react'

export default function CodigoEjemplo({ codigo, lenguaje = 'python', altura = 200 }) {
  return (
    <div className="rounded-xl overflow-hidden border border-[#30363d] my-3">
      <div className="flex items-center gap-2 px-4 py-2 bg-[#161b22] border-b border-[#30363d]">
        <div className="flex gap-1.5">
          <div className="w-3 h-3 rounded-full bg-[#f85149]" />
          <div className="w-3 h-3 rounded-full bg-[#d29922]" />
          <div className="w-3 h-3 rounded-full bg-[#3fb950]" />
        </div>
        <span className="text-xs text-[#8b949e] ml-1 font-mono">{lenguaje}</span>
      </div>
      <Editor
        height={altura}
        language={lenguaje}
        value={codigo}
        theme="vs-dark"
        options={{
          readOnly: true,
          minimap: { enabled: false },
          scrollBeyondLastLine: false,
          fontSize: 14,
          lineNumbers: 'on',
          folding: false,
          wordWrap: 'on',
          scrollbar: { vertical: 'hidden', horizontal: 'hidden' },
          overviewRulerLanes: 0,
          hideCursorInOverviewRuler: true,
          renderLineHighlight: 'none',
          selectionHighlight: false,
          occurrencesHighlight: false,
          padding: { top: 12, bottom: 12 },
          fontFamily: "'JetBrains Mono', 'Fira Code', monospace",
          fontLigatures: true,
        }}
      />
    </div>
  )
}
