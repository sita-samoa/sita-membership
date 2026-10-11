const { spawnSync } = require('node:child_process')
const { writeFileSync } = require('node:fs')

const exceptions = new Set([
  'GHSA-vfj7-8cjw-p6xm',
  'GHSA-rj75-hqrm-r3gf',
])
const reviewDate = '2026-11-08'

function checkAudit(report, today = new Date().toISOString().slice(0, 10)) {
  if (report.error || report.auditReportVersion !== 2 || !report.vulnerabilities || !report.metadata) {
    throw new Error('Invalid or failed npm audit report')
  }
  const findings = Object.entries(report.vulnerabilities)
  const blocked = []
  function isExcepted(name, path = new Set()) {
    const finding = report.vulnerabilities[name]
    if (!finding || path.has(name) || !Array.isArray(finding.via) || !finding.via.length) return false
    const next = new Set([...path, name])
    return finding.via.every(via => {
      if (typeof via === 'string') return isExcepted(via, next)
      return via && typeof via.url === 'string' &&
        exceptions.has(via.url.match(/^https:\/\/github\.com\/advisories\/(GHSA-[a-z0-9-]+)$/)?.[1])
    })
  }
  for (const [name] of findings) {
    if (today >= reviewDate || !isExcepted(name)) blocked.push(name)
  }
  return { blocked, deferred: findings.length - blocked.length }
}

if (require.main === module) {
  try {
    const result = spawnSync('npm', ['audit', '--json'], { encoding: 'utf8' })
    writeFileSync('npm-audit-report.json', result.stdout || '')
    if (result.error || result.signal || ![0, 1].includes(result.status)) {
      throw new Error(result.error?.message || result.stderr || 'npm audit failed')
    }
    const { blocked, deferred } = checkAudit(JSON.parse(result.stdout))
    console.log(`Deferred ${deferred} affected packages; exceptions expire ${reviewDate}.`)
    if (blocked.length) {
      console.error(`Unexcepted or expired findings: ${blocked.join(', ')}`)
      process.exitCode = 1
    }
  } catch (error) {
    console.error(error.message)
    process.exitCode = 1
  }
}

module.exports = { checkAudit }
